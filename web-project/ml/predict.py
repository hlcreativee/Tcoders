from flask import Flask, request, jsonify
import pickle
import pandas as pd
import os

app = Flask(__name__)

# Load model
BASE_DIR = os.path.dirname(__file__)
model_path = os.path.join(BASE_DIR, 'model.pkl')

with open(model_path, 'rb') as f:
    data = pickle.load(f)

model = data['model']
fitur = data['fitur']


@app.route('/predict', methods=['POST'])
def predict():
    try:
        req = request.get_json(force=True)

        if isinstance(req, dict):
            req = [req]

        if not isinstance(req, list):
            return jsonify({"error": "Input harus berupa object atau array"}), 400

        df = pd.DataFrame(req)

        if 'Description' not in df.columns:
            df['Description'] = 'Unknown'

        missing_cols = [col for col in fitur if col not in df.columns]
        if missing_cols:
            return jsonify({
                "error": "Kolom kurang",
                "missing": missing_cols,
                "expected": fitur
            }), 400

        for col in fitur:
            df[col] = pd.to_numeric(df[col], errors='coerce')

        if df[fitur].isnull().any().any():
            return jsonify({
                "error": "Ada nilai null / tidak valid di fitur"
            }), 400
        
        preds = model.predict(df[fitur])

        df_result = pd.DataFrame({
            'Description': df['Description'],
            'prediction': preds
        })

        hasil = (
            df_result
            .groupby('Description')['prediction']
            .sum()
            .sort_values(ascending=False)
            .head(5)
            .to_dict()
        )

        return jsonify({
            "prediction": hasil
        })

    except Exception as e:
        return jsonify({
            "error": str(e)
        }), 500


if __name__ == '__main__':
    app.run(port=5000, debug=True)