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
        req = request.json

        if not isinstance(req, list):
            return jsonify({"error": "Input harus berupa array data"}), 400

        df = pd.DataFrame(req)

        missing_cols = [col for col in fitur if col not in df.columns]
        if missing_cols:
            return jsonify({"error": f"Kolom kurang: {missing_cols}"}), 400

        if 'Description' not in df.columns:
            return jsonify({"error": "Kolom Description wajib ada"}), 400

        desc = df['Description']

        df_model = df[fitur]

        preds = model.predict(df_model)

        df_result = pd.DataFrame({
            'Description': desc,
            'prediction': preds
        })

        hasil = df_result.groupby('Description')['prediction'].sum().to_dict()

        hasil = dict(sorted(hasil.items(), key=lambda x: x[1], reverse=True)[:5])

        return jsonify(hasil)

    except Exception as e:
        return jsonify({"error": str(e)}), 500


if __name__ == '__main__':
    app.run(port=5000, debug=True)