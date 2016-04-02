Para testar a API que desenvolvi é preciso primeiro correr o script da base de dados para a base de dados existir.
Depois, existem os seguintes endpoints e respectivos parâmetros a passar:
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
/comment/hide
	- session_id
	- comment_id