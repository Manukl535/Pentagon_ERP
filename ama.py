import pandas as pd
import json
import sys
import logging
from difflib import get_close_matches, SequenceMatcher

# Set up logging
logging.basicConfig(filename='ama.log', level=logging.DEBUG)

try:
    # Load dataset
    dataset = pd.read_csv('dataset.csv')
    logging.debug("Dataset loaded successfully.")

    # Function to get answer based on user input
    def get_answer(user_input):
        user_input_lower = user_input.lower()
        max_similarity = 0.8  # Adjust the similarity threshold as needed
        best_match = None
        
        # Check for exact match first
        for index, row in dataset.iterrows():
            utterance = row['Example Utterance'].lower()
            if user_input_lower in utterance or utterance in user_input_lower:
                return row['Answer']
        
        # If no exact match, find closest match using get_close_matches
        suggestions = get_close_matches(user_input_lower, dataset['Example Utterance'], n=1, cutoff=max_similarity)
        if suggestions:
            best_match = suggestions[0]
            answer = dataset.loc[dataset['Example Utterance'] == best_match, 'Answer'].iloc[0]
            return answer
        
        # If still no match, use SequenceMatcher to find best match
        for utterance in dataset['Example Utterance']:
            similarity = SequenceMatcher(None, user_input_lower, utterance.lower()).ratio()
            if similarity > max_similarity:
                max_similarity = similarity
                best_match = utterance
                answer = dataset.loc[dataset['Example Utterance'] == best_match, 'Answer'].iloc[0]
        
        return answer if best_match else None

    # Function to suggest close matches
    def suggest_input(user_input):
        utterances = dataset['Example Utterance'].tolist()
        suggestions = get_close_matches(user_input, utterances)
        if suggestions:
            return f"Did you mean: {', '.join(suggestions)}?"
        return "Sorry, I don't understand that question."

    # Handle user input
    if len(sys.argv) > 1:
        user_input = sys.argv[1]
        answer = get_answer(user_input)
        if answer:
            response = {'answer': answer}
        else:
            suggestion = suggest_input(user_input)
            response = {'answer': suggestion}
        print(json.dumps(response))
    else:
        print(json.dumps({'answer': "Sorry, I don't understand that question."}))

except Exception as e:
    logging.error(f"Error: {str(e)}")
    print(json.dumps({'answer': "Sorry, I don't understand that question."}))
