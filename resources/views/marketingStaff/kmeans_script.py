import numpy as np
import mysql.connector

from sklearn.preprocessing import StandardScaler
from sklearn.cluster import KMeans

from datetime import datetime

# Connect to the MySQL database
db_config = {
    "host": "127.0.0.1",
    "port": 3306,
    "user": "root",
    "password": "",
    "database": "crm"
}

with mysql.connector.connect(**db_config) as connection:
    with connection.cursor() as cursor:
        # Step 1: Retrieve data from the customers table
        query = "SELECT id, r_score, f_score, m_score FROM customers"
        cursor.execute(query)
        rows = cursor.fetchall()

        # Convert rows to a NumPy array
        # Assumes the data are numeric
        data_array = np.array(rows, dtype=float)

        # Function to handle negative and zero values
        def handle_neg_n_zero(num):
            if num <= 0:
                return 1
            else:
                return num

        # Apply handle_neg_n_zero function to Recency, Frequency, and Monetary columns
        for i in range(1, 4):
            data_array[:, i] = np.vectorize(
                handle_neg_n_zero)(data_array[:, i])

        # Step 2: Perform log transformation on the selected columns
        log_tfd_data = np.log(data_array[:, 1:])  # Exclude 'id' column

        # Round to three decimal places
        log_tfd_data = np.round(log_tfd_data, 3)

        # Step 3: Initialize and fit the StandardScaler
        scaleobj = StandardScaler()
        scaled_data = scaleobj.fit_transform(log_tfd_data)

        # Step 4: Perform K-Means clustering
        kmeans = KMeans(n_clusters=3, init='k-means++', max_iter=1000)
        cluster_assignments = kmeans.fit_predict(scaled_data)  # Use fit_predict

        # Output cluster assignments
        print(cluster_assignments)

        # Update the c_segment column in the customers table
        for i, row in enumerate(rows):
            customer_id = row[0]
            # Get cluster assignment for this data point
            cluster_segment = cluster_assignments[i]

            # Map cluster assignments to segment labels
            if cluster_segment == 0:
                segment_label = "Silver"
            elif cluster_segment == 1:
                segment_label = "Platinum"
            elif cluster_segment == 2:
                segment_label = "Gold"
            else:
                segment_label = "Unknown"  # Handle any unexpected cluster values

            # Get the current timestamp
            current_timestamp = datetime.now()
            update_query = "UPDATE customers SET c_segment = %s, created_at = %s WHERE id = %s"
            cursor.execute(update_query, (segment_label, current_timestamp, customer_id))

        # Commit the changes
        connection.commit()
