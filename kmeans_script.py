import numpy as np
import mysql.connector
from datetime import datetime

from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans

# Connect to the MySQL database
db_config = {
    "host": "127.0.0.1",
    "port": 3306,
    "user": "root",
    "password": "",
    "database": "crm"
}

# Step 1: Retrieve data from the customers table
with mysql.connector.connect(**db_config) as connection:
    with connection.cursor() as cursor:
        query = "SELECT id, r_score, f_score, m_score FROM customers"
        cursor.execute(query)
        rows = cursor.fetchall()

        # Convert rows to a NumPy array
        data_array = np.array(rows, dtype=float)

# Step 2: Apply handle_neg_n_zero function to Recency, Frequency, and Monetary columns


def handle_neg_n_zero(num):
    if num <= 0:
        return 1
    else:
        return num


for i in range(1, 4):
    data_array[:, i] = np.vectorize(handle_neg_n_zero)(data_array[:, i])

# Calculate RFM segment values
quantiles = np.percentile(data_array[:, 1:], q=[25, 50, 75], axis=0)
quantiles_dict = {
    'Recency': quantiles[0],
    'Frequency': quantiles[1],
    'Monetary': quantiles[2]
}

# Scoring functions


def RScoring(x, d):
    if x <= d[0]:
        return 1
    elif x <= d[1]:
        return 2
    elif x <= d[2]:
        return 3
    else:
        return 4


def FnMScoring(x, d):
    if x <= d[0]:
        return 4
    elif x <= d[1]:
        return 3
    elif x <= d[2]:
        return 2
    else:
        return 1


# Calculate R, F, and M segment values
# Create an array for new columns
new_columns = np.zeros((data_array.shape[0], 3))
for i, row in enumerate(data_array):
    r_score = row[1]
    f_score = row[2]
    m_score = row[3]

    new_columns[i, 0] = RScoring(r_score, quantiles_dict['Recency'])
    new_columns[i, 1] = FnMScoring(f_score, quantiles_dict['Frequency'])
    new_columns[i, 2] = FnMScoring(m_score, quantiles_dict['Monetary'])

# Concatenate new columns to the original data_array
data_array_with_scores = np.concatenate((data_array, new_columns), axis=1)

print("First row of data_array:")
print("ID:", data_array_with_scores[1, 0])
print("R Score:", data_array_with_scores[1, 1])
print("F Score:", data_array_with_scores[1, 2])
print("M Score:", data_array_with_scores[1, 3])
print("R:", data_array_with_scores[1, 4])
print("F:", data_array_with_scores[1, 5])
print("M:", data_array_with_scores[1, 6])

# Step 3: Perform log transformation on the selected columns
log_tfd_data = np.log(data_array_with_scores[:, 1:4])

# Round to three decimal places
log_tfd_data = np.round(log_tfd_data, 3)

# Step 4: Initialize and fit the StandardScaler
scaleobj = StandardScaler()
scaled_data = scaleobj.fit_transform(log_tfd_data)

print("Scaled Data:")
print(scaled_data)

# Step 5: Perform K-Means clustering
kmeans = KMeans(n_clusters=3, init='k-means++', max_iter=1000, n_init=10)
cluster_assignments = kmeans.fit_predict(scaled_data)

print(cluster_assignments)

# update the c_segment in customer table
with mysql.connector.connect(**db_config) as connection:
    with connection.cursor() as cursor:
        # Update the c_segment column in the customers table
        for i, row in enumerate(rows):
            customer_id = row[0]
            # Get cluster assignment for this data point
            cluster_segment = cluster_assignments[i]
            

            update_query = "UPDATE customers SET c_segment = %s, updated_at = %s WHERE id = %s"
            current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute(update_query, (str(cluster_segment), current_time, customer_id))

        # Commit the changes
        connection.commit()

# Step 6: Determine loyalty levels for clusters based on mean RFM scores

# Calculate Mean RFM Scores for Each Cluster
with mysql.connector.connect(**db_config) as connection:
    with connection.cursor() as cursor:
        unique_segments = np.unique(cluster_assignments)
        cluster_mean_rfm = []

        for segment in unique_segments:
            segment_indices = np.where(cluster_assignments == segment)[0]
            segment_scores = data_array_with_scores[segment_indices, 4:7]
            segment_sum = np.sum(segment_scores)  # Calculate sum of R + F + M
            # Calculate mean based on sum and count
            segment_mean = segment_sum / len(segment_indices)
            cluster_mean_rfm.append(segment_mean)

        cluster_mean_rfm = np.array(cluster_mean_rfm)

print(cluster_mean_rfm)

with mysql.connector.connect(**db_config) as connection:
    with connection.cursor() as cursor:
        loyalty_levels = []

        if cluster_mean_rfm[0] == np.max(cluster_mean_rfm) and cluster_mean_rfm[1] == np.min(cluster_mean_rfm) :
            loyalty_levels = ["Silver", "Platinum", "Gold"]
        if cluster_mean_rfm[0] == np.max(cluster_mean_rfm) and cluster_mean_rfm[2] == np.min(cluster_mean_rfm) :
            loyalty_levels = ["Silver", "Gold", "Platinum"]
        if cluster_mean_rfm[0] == np.min(cluster_mean_rfm) and cluster_mean_rfm[2] == np.max(cluster_mean_rfm) :
            loyalty_levels = ["Platinum", "Gold", "Silver"]
        if cluster_mean_rfm[0] == np.min(cluster_mean_rfm) and cluster_mean_rfm[1] == np.max(cluster_mean_rfm) :
            loyalty_levels = ["Platinum", "Silver", "Gold"]
        if cluster_mean_rfm[1] == np.min(cluster_mean_rfm) and cluster_mean_rfm[2] == np.max(cluster_mean_rfm) :
            loyalty_levels = ["Gold", "Platinum", "Silver"]
        if cluster_mean_rfm[1] == np.max(cluster_mean_rfm) and cluster_mean_rfm[2] == np.min(cluster_mean_rfm) :
            loyalty_levels = ["Gold", "Silver", "Platinum"]

        print(loyalty_levels)
        # Step 7: Update the c_segment column in the customers table based on cluster loyalty levels
        for i, row in enumerate(rows):
            customer_id = row[0]
            cluster_loyalty = loyalty_levels[cluster_assignments[i]]

            update_query = "UPDATE customers SET c_segment = %s, updated_at = %s WHERE id = %s"
            current_time = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute(update_query, (cluster_loyalty, current_time, customer_id))

        connection.commit()