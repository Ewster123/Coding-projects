import pandas as pd

df = pd.read_csv('pokemon_data.csv') # loading in the files
print(df.head(3)) # top 3 rows
print(df.tail(3)) # bottom 3 rows

print(df.columns) # prints colums / headers

print(df['Name']) # prints pokemons names make sure header is capital at start

print(df.head(4)) # prints the diffrent rows

print(df.iloc[1:4]) # prints 4 rows , row 1 to 4

print(df.iloc[2,1]) # finds specific things that you want to print [r,c]

# Filtering data based on a condition
filtered_data = df[df['Type 1'] == 'Water']

# Using the query() method
filtered_data = df.query('HP > 80')

# Adding a new column
df['Total_Stats'] = df['HP'] + df['Attack'] + df['Defense'] + df['Sp. Atk'] + df['Sp. Def'] + df['Speed']

# Deleting a column
df.drop(columns=['Total_Stats'], inplace=True)

# Modifying a column
df['Generation'] = df['Generation'].map({1: 'Gen I', 2: 'Gen II', 3: 'Gen III', ...})

# Grouping by Type 1 and calculating mean stats
grouped_data = df.groupby('Type 1').mean()

# Aggregating multiple statistics
aggregated_data = df.groupby('Type 1').agg({'Attack': 'mean', 'Defense': 'median', 'Speed': 'max'})

# Handling missing data (filling with mean)
df.fillna(df.mean(), inplace=True)

# Dropping duplicate entries
df.drop_duplicates(inplace=True)


# Merge two DataFrames based on a common column
merged_df = pd.merge(df1, df2, on='common_column')

# Join two DataFrames based on index
joined_df = df1.join(df2, lsuffix='_left', rsuffix='_right')
