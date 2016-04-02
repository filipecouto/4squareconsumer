To test the API I developed first you need to run the database script located on /sql/database.sql
Then, you have the following endpoints:
/places/search GET
	- where || latitude && longitude
	- query
/places/addDescription POST
	- session_id
	- place_external_id
	- description
/places/editDescription POST
	- session_id
	- description_id
	- description
/places/comment POST
	- session_id
	- description_id
	- comment
/users/register POST
	- email
	- password
/comment/hide POST
	- session_id
	- comment_id
