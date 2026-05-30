cd database
sqlite3 gym.db < gym_schema.sql
sqlite3 gym.db < populate_assessment.sql
cd ..
cd utils
rm -f .last_cron_exec.txt