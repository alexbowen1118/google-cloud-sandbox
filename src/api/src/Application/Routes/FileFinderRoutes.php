<?php

namespace DPR\API\Application\Routes;

# BusinessUnit
use DPR\API\Application\Actions\FileFinder\BusinessUnit\GetBusinessUnitsAction;
use DPR\API\Application\Actions\FileFinder\BusinessUnit\CreateBusinessUnitAction;
use DPR\API\Application\Actions\FileFinder\BusinessUnit\BusinessUnitIncrementCountAction;
use DPR\API\Application\Actions\FileFinder\BusinessUnit\BusinessUnitUsageChangeAction;
# DocumentType
use DPR\API\Application\Actions\FileFinder\DocumentType\GetDocumentTypesAction;
use DPR\API\Application\Actions\FileFinder\DocumentType\CreateDocumentTypeAction;
use DPR\API\Application\Actions\FileFinder\DocumentType\DocumentTypeUsageChangeAction;
# Tag
use DPR\API\Application\Actions\FileFinder\Tag\GetTagsAction;
use DPR\API\Application\Actions\FileFinder\Tag\CreateTagAction;
use DPR\API\Application\Actions\FileFinder\Tag\TagUsageChangeAction;
# File
use DPR\API\Application\Actions\FileFinder\File\GetFilesAction;
use DPR\API\Application\Actions\FileFinder\File\FileArchiveStatusChangeAction;
use DPR\API\Application\Actions\FileFinder\File\GetFilePresignedUrlAction;
use DPR\API\Application\Actions\FileFinder\File\DeleteFileAction;
# Topic
use DPR\API\Application\Actions\FileFinder\Topic\ViewTopicAction;
use DPR\API\Application\Actions\FileFinder\Topic\EditTopicAction;
# Upload
use DPR\API\Application\Actions\FileFinder\Upload\UploadFilesAction;
# DeleteRequest
use DPR\API\Application\Actions\FileFinder\DeleteRequest\CreateDeleteRequestAction;
use DPR\API\Application\Actions\FileFinder\DeleteRequest\DeleteRequestResponseAction;
# Topic
use DPR\API\Application\Actions\FileFinder\Topic\TopicUsageChangeAction;
#Search
use DPR\API\Application\Actions\FileFinder\Search\SearchFilesAction;
#
use Slim\Routing\RouteCollectorProxy;

class FileFinderRoutes
{

    function __invoke(RouteCollectorProxy $group)
    {
        # BusinessUnit
        $group->get('/businessunits', GetBusinessUnitsAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->post('/businessunits', CreateBusinessUnitAction::class)->setArgument("permissions", "Admin, Super-Admin");
        $group->patch('/businessunits/{id}/count', BusinessUnitIncrementCountAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->patch('/businessunits/{id}/active', BusinessUnitUsageChangeAction::class)->setArgument("permissions", "Admin, Super-Admin");

        # DocumentType
        $group->get('/documenttypes', GetDocumentTypesAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->post('/documenttypes', CreateDocumentTypeAction::class)->setArgument("permissions", "Admin, Super-Admin");
        $group->patch('/documenttypes/{id}/active', DocumentTypeUsageChangeAction::class)->setArgument("permissions", "Admin, Super-Admin");

        # Tag
        $group->get('/tags', GetTagsAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->post('/tags', CreateTagAction::class)->setArgument("permissions", "Admin, Super-Admin");
        $group->patch('/tags/{id}/active', TagUsageChangeAction::class)->setArgument("permissions", "Admin, Super-Admin");

        # Upload
        $group->post('/upload', UploadFilesAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");

        #Search
        $group->get('/search', SearchFilesAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");

        #File
        $group->get('/files', GetFilesAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->get('/files/s3url/{objectName}', GetFilePresignedUrlAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->patch('/files/{id}/archive', FileArchiveStatusChangeAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->delete('/files/{id}', DeleteFileAction::class)->setArgument("permissions", "Admin, Super-Admin");

        #Topic
        $group->get("/topics/{id}", ViewTopicAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");
        $group->patch("/topics/{id}/edit", EditTopicAction::class)->setArgument("permissions", "Base, Manager, Admin, Super-Admin");

        # DeleteRequest
        $group->post('/deleterequests', CreateDeleteRequestAction::class)->setArgument("permissions", "Base");
        $group->put('/deleterequests/{id}/response', DeleteRequestResponseAction::class)->setArgument("permissions", "Manager, Admin, Super-Admin");

        # Topic
        $group->patch('/topics/{id}/active', TopicUsageChangeAction::class)->setArgument("permissions", "Admin, Super-Admin");
    }
}
