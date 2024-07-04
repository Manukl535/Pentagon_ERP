import pandas as pd

# Load dataset
dataset = pd.read_csv('dataset1.csv')

# dataset columns are: 'Example Utterance', 'Category', 'Intent', 'Answer'

# Appropriate answer based on user input
def get_answer(user_input, dataset):
    # Iterate through the dataset to find matching user input
    for index, row in dataset.iterrows():
        utterance = row['Example Utterance']
        if user_input.lower() in utterance.lower():
            return row['Answer']
    return "Sorry, I don't understand that question."

# Interaction loop
while True:
    user_question = input("User: ")
    if user_question.lower() == 'exit':
        print("Exiting...")
        break
    answer = get_answer(user_question, dataset)
    print(f"Bot: {answer}")
