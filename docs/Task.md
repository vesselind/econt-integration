# Creating PHP Econt Integration Library

You should create a standalone PHP library integration of Econt Delivery services (Econt API). 
This library should be designed to be reusable across multiple projects.
Should be compliant mostly with PHP 8.1 and above and Symfony framework v. 7.0 and v. 8.0.
It could use the http client of Symfony, but it should be designed in a way that allows it to be used in other contexts as well.
The library should be well-documented, with clear instructions on how to install and use it. (Create README.md file with installation and usage instructions)
The library should include unit tests to ensure its functionality and reliability. (Use PHPUnit for testing).
All requests and responses should be mapped to Model objects, and the library should provide a clear and intuitive API for interacting with the Econt services.
Models classes properties should be in camelCase and should be properly typed. However, the models should be back convertable to array with the associative keys of the original API.
You must explore and thoroughly understand the Econt API documentation to ensure that all necessary endpoints and functionalities are covered in the library, located in /docs/EcontDocs directory of this project.
Follow SOLID and other best practices in your implementation to ensure that the library is maintainable and extensible.
At the highest priority is implementing the functionality of retrieving offices, streets, cities, and countries from the Econt API. This will serve as the foundation for the library and will allow us to build additional features on top of it in the future.
Other functionalities, part of the provided documentation should be covered as well.
If neccessary - for serializing and deserializing the data, you can use the Symfony Serializer library as dependency.
You should create test with real fetch to the real api with testing credentials to ensure the data is properly retrieved, mapped and overall processed.

