# Drupal 8 Module Development Challenge

For this challenge, we would like you to create a custom module. Feel free to consult with any Drupal documentation or articles on the web to complete the challenge. In your custom module:

Create a node type called **“superhero”**.
You will need to store **id**, **name**, **superpower**.
Create a few nodes of this type (this can be manual).

1. Create a custom service to manage the display of superheros.
2. Add a method **randomHero** which displays a random superhero from the superhero nodes. It should display the superhero this way:
    * My name is **[name]** and I have the power of **[superpower]**.
    * I was born **[x]** minutes ago. (the number of minutes since the node was created).
    * My creator is **[username]**. (the owner of the node).

3. Create a custom block that displays a random superhero using your service.
    * We are ideally looking for dependency injection of the service into the block.

4. (Bonus) Expose superheroes as a headless rest resource.
    * Superheroes should be available on the path **“/hero/{hero_id}”**
    * The hero_id parameter should map to node id.
    * It should output the movie in a simple flat JSON. Example:

#### GET /hero/1?_format=json

```json
{
  "id": 1,
  "name": "The Hulk",
  "superpower": "Super Strength when I get ANGRY"
}
```


## Skills We're Assessing For
* Drupal 8 module development
* Knowledge of services and dependency injection
* Problem-solving, analytical and creative ability
* Reliability/perseverance
* Attention to detail
* Initiative/ability-to-learn
* Best practice and standards used
