from flask import Flask, request, jsonify
import pickle
import pandas as pd
import os

app = Flask(__name__)

BASE_DIR = os.path.dirname(__file__)
model_path = os.path.join(BASE_DIR, 'model.pkl')

with open(model_path, 'rb') as f:
    data = pickle.load(f)

model = data['model']
fitur = data['fitur']

@app.route('/predict', methods=['POST'])
def predict():
    req = request.json

    df = pd.DataFrame([req])
    df = df[fitur]

    pred = model.predict(df)[0]

    return jsonify({"prediction": float(pred)})

if __name__ == '__main__':
    app.run(port=5000)