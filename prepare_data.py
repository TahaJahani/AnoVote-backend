import bcrypt
import pandas as pd


def get_year(student_number: str):
    if student_number.startswith('4'):
        return int(student_number[:3])
    return int(student_number[:2])


def get_grade(student_number: str):
    if student_number.startswith('4'):
        return int(student_number[3])
    return int(student_number[2])


salt = bcrypt.gensalt()

to_write = []
df = pd.read_csv('data.csv')
df = df.reset_index()

for index, row in df.iterrows():
    to_hash = str(row['student_number']) + " - " + str(row['national_id'])
    item = {
        "password": bcrypt.hashpw(to_hash.encode('utf-8'), salt),
        "year": get_year(str(row['student_number'])),
        "grade": get_grade(str(row['student_number']))
    }
    to_write.append(item)


to_write = pd.DataFrame(to_write)
to_write.to_csv("hashed_info.csv")
