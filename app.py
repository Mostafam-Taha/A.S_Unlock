from flask import Flask, render_template

app = Flask(__name__)

@app.route('/')
def home():
    return render_template('admin/dashoard.php')

if __name__ == '__main__':
    app.run(debug=True)