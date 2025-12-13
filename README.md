<img src="https://avatars.githubusercontent.com/u/70107733?s=128" width="100" alt="Elephant Studio">

Elephant Studio :: Laravel database tools
============

- [Faker](src/Faker/Faker.php) - Renamed standard Laravel Factory. Factory pattern should be used to create Entities and Models, not fake data.
- [Migration](src/Migration/Migration.php) - Extended Laravel Migration disallowing `down()` to reduce production risks.
- Model
  - [Model](src/Model/Model.php) - Extended Laravel Model with changes list allowing to get all Model changes even after saving.
  - [ModelTrait](src/Model/ModelTrait.php) - Extended Laravel Model Trait for pre-built models
- [Repository](src/Repository/Repository.php) - Standard practice is to use Repositories instead of direct Model Query building in Controllers or Services. Repository holds `save()` and `delete()` methods as default, it also attached changes list to `Model` on every `save()`.
- Seeder
  - [Seeder](src/Seeder/Seeder.php) - Extended Laravel Seeder with added `getDependencies()` method allowing to order Seeders.
  - [SeedersRunner](src/Seeder/SeedersRunner.php) - Application `DatabaseSeeder` should extend this class to enable extended Seeders logic.
