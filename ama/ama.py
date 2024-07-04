import pandas as pd
import datetime

# Load dataset
dataset = pd.read_csv('dataset1.csv')

# dataset columns are: 'Example Utterance', 'Category', 'Intent', 'Answer'

# Function to get appropriate greeting based on current time
def get_greeting():
    current_time = datetime.datetime.now()
    if current_time.hour < 12:
        return "Good morning! I'm AMA. How can I assist you today?"
    elif 12 <= current_time.hour < 18:
        return "Good afternoon! I'm AMA. How can I assist you today?"
    else:
        return "Good evening! I'm AMA. How can I assist you today?"

# Function to get answer based on user input
def get_answer(user_input, dataset):
    # Iterate through the dataset to find matching user input
    for index, row in dataset.iterrows():
        utterance = row['Example Utterance']
        if user_input.lower() in utterance.lower():
            return row['Answer']
    return "Sorry, I don't understand that question."

# Initial greeting with "Example AMA: " prefix
print(f"AMA: {get_greeting()}")

# Interaction loop
while True:
    user_question = input("User: ")
    
    if user_question.lower() == 'exit':
        print("Exiting...")
        break
    
    if user_question.lower() == 'hello' or user_question.lower() == 'hi':
        print(f"AMA: {get_greeting()}")
        continue
    
    answer = get_answer(user_question, dataset)
    print(f"AMA: {answer}")
