import streamlit as st
import joblib
import numpy as np

# Load the trained model
best_rf = joblib.load("best_rf_model.pkl")

st.title("Rainfall Prediction App")

# Input fields
prev_rainfall = st.number_input("Previous Day Rainfall (mm)", min_value=0.0, step=0.1)
month = st.number_input("Month (1-12)", min_value=1, max_value=12, step=1)
day_of_year = st.number_input("Day of Year (1-365)", min_value=1, max_value=365, step=1)

# Prediction button
if st.button("Predict"):
    input_data = np.array([[prev_rainfall, month, day_of_year]])
    prediction = best_rf.predict(input_data)
    st.success(f"Predicted Rainfall: {prediction[0]:.2f} mm")
