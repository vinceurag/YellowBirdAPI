# Yellow Bird Cafe x Kitchen REST API
A fully RESTful server implementation via Codeigniter and REST_Controller library for the online reservation feature. JSON Web Token was used in implementing authorization

## API Endpoints
###`/auth` - for token generation
####Parameters
#####POST
######`username` - user's username
######`password` - user's password
###`/reservations` - posting and getting reservations
####Parameters
#####POST
######"`date_f` - Date of reservation
######`time_f` - Time of reservation
######`name_mobile` - Name and mobile number of customer
######`numOfSeats` - Number of seats
######`tableType` - Type of table (e.g., couch, high chair)
#####GET
######`Authorization` - JSON Web TOken. Format: Authorization: Bearer eyjdsa.dasda.dasdsa
###`/reservations/confirm/{id}` - confirmation of a single reservation
####Parameters
#####POST
######`id` - ID of reservation to be confirmed
######`Authorization` - JSON Web TOken. Format: Authorization: Bearer eyjdsa.dasda.dasdsa
