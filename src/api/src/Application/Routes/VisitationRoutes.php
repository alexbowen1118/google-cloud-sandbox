<?php

namespace DPR\API\Application\Routes;

use DPR\API\Application\Actions;
use DPR\API\Domain\Webhook;

use DPR\API\Application\Middleware\TokenAuthMiddleware;
use DPR\API\Application\Middleware\UbidotsAuthMiddleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

class VisitationRoutes {
  
  function __invoke(RouteCollectorProxy $group) {

    //**DEVICES**//
    $group->group('/devices', function ($group) {
      // Gets all devices at all parks
      $group->get('', Actions\Visitation\Devices\GetDevicesAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a device by the ID
      $group->get('/{dev_id}', Actions\Visitation\Devices\GetDeviceAction::class)
        ->setArgument("permissions", "ALL");
      
      // Updates a device by the ID
      $group->put('/{dev_id}', Actions\Visitation\Devices\UpdateDeviceAction::class)
        ->setArgument("permissions", "Manager, Admin, SuperAdmin");
      
      // Adds a device
      $group->post('', Actions\Visitation\Devices\CreateDeviceAction::class)
        ->setArgument("permissions", "Manager, Admin, SuperAdmin");
      
      // Deletes a device by the ID
      $group->delete('/{dev_id}', Actions\Visitation\Devices\DeleteDeviceAction::class)
        ->setArgument("permissions", "Manager, Admin, SuperAdmin");
      
    })->add(TokenAuthMiddleware::class);

    // Gets all the devices for a specific park
    $group->get('/parks/{par_id}/devices', Actions\Visitation\Devices\GetDevicesByParkAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);




    //**BRANDS**//
    $group->group('/brands', function ($group) {
      // Gets all brands
      $group->get('', Actions\Visitation\Brands\GetBrandsAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a brand by the ID
      $group->get('/{brn_id}', Actions\Visitation\Brands\GetBrandAction::class)
        ->setArgument("permissions", "ALL");
      
      // Updates a brand by the ID
      $group->put('/{brn_id}', Actions\Visitation\Brands\UpdateBrandAction::class)
        ->setArgument("permissions", "ALL");
      
      // Adds a brand
      $group->post('', Actions\Visitation\Brands\CreateBrandAction::class)
        ->setArgument("permissions", "ALL");
      
      // Deletes a brand by the ID
      $group->delete('/{brn_id}', Actions\Visitation\Brands\DeleteBrandAction::class)
        ->setArgument("permissions", "ALL");
      
    })->add(TokenAuthMiddleware::class);




    //**FUNCTIONS**//
    $group->group('/functions', function ($group) {
      // Gets all functions
      $group->get('', Actions\Visitation\Functions\GetFunctionsAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a function by the ID
      $group->get('/{fnc_id}', Actions\Visitation\Functions\GetFunctionAction::class)
        ->setArgument("permissions", "ALL");
      
      // Updates a function by the ID
      $group->put('/{fnc_id}', Actions\Visitation\Functions\UpdateFunctionAction::class)
        ->setArgument("permissions", "ALL");
      
      // Adds a function
      $group->post('', Actions\Visitation\Functions\CreateFunctionAction::class)
        ->setArgument("permissions", "ALL");
      
      // Deletes a function by the ID
      $group->delete('/{fun_id}', Actions\Visitation\Functions\DeleteFunctionAction::class)
        ->setArgument("permissions", "ALL");

    })->add(TokenAuthMiddleware::class);




    //**MODELS**//
    $group->group('/models', function ($group) {
      // Gets all models
      $group->get('', Actions\Visitation\Models\GetModelsAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a model by the ID
      $group->get('/{mdl_id}', Actions\Visitation\Models\GetModelAction::class)
        ->setArgument("permissions", "ALL");
      
      // Updates a model by the ID
      $group->put('/{mdl_id}', Actions\Visitation\Models\UpdateModelAction::class)
        ->setArgument("permissions", "ALL");
      
      // Adds a model
      $group->post('', Actions\Visitation\Models\CreateModelAction::class)
        ->setArgument("permissions", "ALL");
      
      // Deletes a model by the ID
      $group->delete('/{mdl_id}', Actions\Visitation\Models\DeleteModelAction::class)
        ->setArgument("permissions", "ALL");
      
    })->add(TokenAuthMiddleware::class);




    //**METHODS**//
    $group->group('/methods', function ($group) {
      // Gets all methods
      $group->get('', Actions\Visitation\Methods\GetMethodsAction::class)
        ->setArgument("permissions", "ALL");

      // Gets a method by the ID
      $group->get('/{mtd_id}', Actions\Visitation\Methods\GetMethodAction::class)
        ->setArgument("permissions", "ALL");

      // Updates a method by the ID
      $group->put('/{mtd_id}', Actions\Visitation\Methods\UpdateMethodAction::class)
        ->setArgument("permissions", "ALL");

      // Adds a method
      $group->post('', Actions\Visitation\Methods\CreateMethodAction::class)
        ->setArgument("permissions", "ALL");

      // Deletes a method by the ID 
      $group->delete('/{mtd_id}', Actions\Visitation\Methods\DeleteMethodAction::class)
        ->setArgument("permissions", "ALL");

    })->add(TokenAuthMiddleware::class);




    //**TYPES**//
    $group->group('/types', function ($group) {
      // Gets all types
      $group->get('', Actions\Visitation\Types\GetTypesAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a type by the ID
      $group->get('/{typ_id}', Actions\Visitation\Types\GetTypeAction::class)
        ->setArgument("permissions", "ALL");
      
      // Updates a type by the ID
      $group->put('/{typ_id}', Actions\Visitation\Types\UpdateTypeAction::class)
        ->setArgument("permissions", "ALL");
      
      // Adds a type
      $group->post('', Actions\Visitation\Types\CreateTypeAction::class)
        ->setArgument("permissions", "ALL");
      
      // Deletes a type by the ID
      $group->delete('/{typ_id}', Actions\Visitation\Types\DeleteTypeAction::class)
        ->setArgument("permissions", "ALL");

    })->add(TokenAuthMiddleware::class);




    //**COUNTER RULES**//
    // Gets all counter rules for all devices
    $group->get('/counter_rules', Actions\Visitation\CounterRules\GetCounterRulesAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);

    $group->group('/devices/{dev_id}/counter_rules', function ($group) {
      // Gets all counter rules for a device
      $group->get('', Actions\Visitation\CounterRules\GetCounterRulesByDeviceAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a counter rule by the ID for a device
      $group->get('/{rul_id}', Actions\Visitation\CounterRules\GetCounterRuleAction::class)
        ->setArgument("permissions", "ALL");
      
      // Updates a counter rule by the ID for a device
      $group->put('/{rul_id}', Actions\Visitation\CounterRules\UpdateCounterRuleAction::class)
        ->setArgument("permissions", "Admin, SuperAdmin");
      
      // Adds a counter rule for a device
      $group->post('', Actions\Visitation\CounterRules\CreateCounterRuleAction::class)
        ->setArgument("permissions", "Admin, SuperAdmin");
      
      // Deletes a counter rule by the ID
      $group->delete('/{rul_id}', Actions\Visitation\CounterRules\DeleteCounterRuleAction::class)
        ->setArgument("permissions", "Admin, SuperAdmin");

    })->add(TokenAuthMiddleware::class);




    //**VISITS**//
    $group->group('/visits', function ($group) {
      // Gets all visits at all parks for all devices
      $group->get('', Actions\Visitation\Visits\GetVisitsAction::class)
        ->setArgument("permissions", "ALL");

      // Gets all visits aggregated into months
        $group->get('/month', Actions\Visitation\Visits\GetMonthVisitsAction::class)
        ->setArgument("permissions", "ALL");
      
      // Gets a visit by the ID
      $group->get('/{vis_id}', Actions\Visitation\Visits\GetVisitAction::class)
        ->setArgument("permissions", "ALL");
    })->add(TokenAuthMiddleware::class);
    
    // Gets all visits for a specific device
    $group->get('/devices/{dev_id}/visits', Actions\Visitation\Visits\GetVisitsByDeviceAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);
    
    // Updates a visit by the device and visit ID
    $group->put('/devices/{dev_id}/visits/{vis_id}', Actions\Visitation\Visits\UpdateVisitAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);
    
    // Adds a visit for a specific device
    $group->post('/devices/{dev_id}/visits', Actions\Visitation\Visits\CreateVisitAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);
    
    // Deletes a visit by the device and visit ID
    $group->delete('/devices/{dev_id}/visits/{vis_id}', Actions\Visitation\Visits\DeleteVisitAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);

    // Gets all visits for a specific park
    $group->get('/parks/{par_id}/visits', Actions\Visitation\Visits\GetVisitsByParkAction::class)
      ->setArgument("permissions", "ALL")
      ->add(TokenAuthMiddleware::class);

      // Gets all visits for a specific park aggregated into days
      $group->get('/parks/{par_id}/visits/day', Actions\Visitation\Visits\GetDayVisitsByParkAction::class)
          ->setArgument("permissions", "ALL")
          ->add(TokenAuthMiddleware::class);





    //**UBIDOTS API CALLS**//
    $group->group('/fetch', function ($group) {
      $group->get('/devices', Actions\Visitation\Devices\FetchLegacyDevicesAction::class);
      $group->get('/visits', Actions\Visitation\Devices\FetchLegacyVisitsAction::class);
    })->add(UbidotsAuthMiddleware::class);

    //**WEBHOOK**//
    $group->post('/incoming', Actions\Visitation\Visits\CreateVisitFromWebhookAction::class)
      ->add(UbidotsAuthMiddleware::class);
  }
}
