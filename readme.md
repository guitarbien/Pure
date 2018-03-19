## Structure

- bin : Something like artisan command, it's just a entrance to execute the command for once.
- migrations : Put all your migrations here.
- public : Things here should be public, like index.php, /css, /js, /img
- src
    - Framework
        - Csrf
        - Dbal
        - MessageContainer
        - Rendering
    - {FEATURE_NAME}
        - **Application**: The application layer represents the possible interactions between the outside world and your application - the queries and commands that can be executed by the frontend. Application layer is also called service layer or use case layer.
        - **Domain**: The code for the business logic of your application resides in this layer.
        - **Infrastructure**: Here place the CRUD repository or query builder.
        - **Presentation**: Presentation layer encompasses everything from receiving a request from a user to returning an appropriate response.
- storage : The uploaded data, or sqlite file.
- templates : The html template files are here.
- vendor : Of course the composer packages.

