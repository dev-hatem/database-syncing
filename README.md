<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


```
if you want to use the same model for both central db and tenant db => implement all interfaces + override the needed methods and use the needed traits
```
#### How to sync table (model) between tenant db and central db 

> Make the central db updated not the reverse

##### Step 1
- Make new Model with migration file for central table
- In central model implement this interface

`
use App\Interfaces\SyncableMaster
`

- Implement this method this method return the column name 
which will store the id value of tenant table

`
 getCentralForeignKeyName()
`
- Finally Add guarded or fillable property with your needed columns

`
  protected $guarded = [];
`
##### Step 2

- In tenant model implement this interface

`
use App\Interfaces\Syncable;
`

- And also use this traits

`
use App\Traits\DatabaseSyncing;
`
- Implement this methods
this method return the array of column names which will be synced with the central database

`
getSyncedAttributeNames()
`

- Implement this methods
this method return the equivalent central model class which represent the central database


`
getSyncedAttributeNames()
`

#### Example

> Central Vehicle Model is

```
<?php

namespace App\Models\Synced;

use App\Interfaces\SyncableMaster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Vehicle extends Model implements SyncableMaster
{
    use HasFactory, CentralConnection;

    protected $guarded = [];

    public function getCentralForeignKeyName(): string
    {
        return 'vehicle_id';
    }

}
```

> Tenant Vehicle Model is

```php
<?php

namespace Modules\Vehicle\Entities;

use App\Interfaces\Syncable;
use App\Traits\DatabaseSyncing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;



class Vehicle extends Model implements Syncable
{
    use HasFactory, SoftDeletes, DatabaseSyncing;

    protected $fillable  = ['owner_id', 'vin', 'year_number', 'class', 'description', 'msrp', 'model_id', 'odometer', 'purchase_date', 'in_service_date', 'creation_date', 'modification_date'];

    public function getSyncedAttributeNames(): array
    {
        return [
            'vin', 'year_number', 'description', 'class',
            'odometer', 'msrp', 'purchase_date', 'in_service_date',
            'creation_date', 'modification_date', 'deleted_at'
        ];
    }

    public function getCentralModelName(): string
    {
        return \App\Models\Synced\Vehicle::class;
    }

}

```
> Migration of central table
```php
    Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('vin')->nullable();
            $table->string('year_number')->nullable();
            $table->json('description')->nullable();
            $table->string('class')->nullable();
            $table->decimal('odometer', 12,2)->nullable();
            $table->decimal('msrp', 12,2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('in_service_date')->nullable();
            $table->date('creation_date')->nullable();
            $table->date('modification_date')->nullable();
            $table->foreignId('tenant_id')->constrained('tenants', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['tenant_id', 'vehicle_id']);
            $table->timestamps();
            $table->dateTime('deleted_at');
        });
```

> Migration of tenant table

```php
 Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vin')->unique();
            $table->year('year_number');
            $table->json('description')->nullable();
            $table->string('class')->nullable();
            $table->decimal('odometer', 12,2)->nullable();
            $table->decimal('msrp', 12,2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('in_service_date')->nullable();
            $table->date('creation_date')->nullable();
            $table->date('modification_date')->nullable();
            $table->foreignId('model_id')->references('id')->on('models')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('owner_id')->references('id')->on('third_parties')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
```

> You can customize the and just select and migrate only your needed columns

#### How to sync pivot table (many to many relation) 

#### step 1

> in each class in the many to many relation add this in the relation

```php
  use \App\Models\SyncPivot::class;
  
  
   public function users()
      {
          return $this->belongsToMany(User::class,'project_user', 'project_id', 'user_id')
              ->using(SyncPivot::class);
      }
      
```
> Important make sure  ```to make the (many to many) table name in central database with the same name of tenant database```
```
if the same model used dont use the CentralConnection trait
```
