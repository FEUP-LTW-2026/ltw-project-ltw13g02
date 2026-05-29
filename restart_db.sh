cd database
sqlite3 gym.db < gym_schema.sql
sqlite3 gym.db < populate.sql
cd ..