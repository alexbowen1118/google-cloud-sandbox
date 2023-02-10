<?php
declare(strict_types=1);
namespace DPR\API\Infrastructure\Persistence\DAO;

use DPR\API\Infrastructure\Persistence\DAO\FileFinder\BusinessUnitDAO;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\DocumentTypeDAO;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\TagDAO;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\TopicDAO;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\UploadDAO;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\SearchDAO;
use DPR\API\Infrastructure\Persistence\DAO\FileFinder\FileDAO;

use DPR\API\Infrastructure\Persistence\DBPool;

class DAOFactory {
  private $DBPool;

  const BUSINESS_UNIT_DAO = 'BusinessUnitDAO';
  const DOCUMENT_TYPE_DAO = 'DocumentTypeDAO';
  const TAG_DAO = 'TagDAO';
  const PARK_DAO = 'ParkDAO';
  const FILE_DAO = 'FileDAO';
  const TOPIC_DAO = 'TopicDAO';
  const UPLOAD_DAO = 'UploadDAO';
  const SEARCH_DAO = 'SearchDAO';
  const DELETE_REQUEST_DAO = 'DeleteRequestDAO';

  public function __construct(DBPool $DBPool) {
    $this->DBPool = $DBPool;
  }

  public function __get($property): DAO {
    $className = 'DPR\\API\\Infrastructure\\Persistence\\DAO\\'.$property;

    return new $className($this->DBPool);
  }

  public function createAuthenticationDAO(): DAO {
    return new AuthenticationDAO($this->DBPool);
  }

  public function createBrandDAO(): DAO {
    return new BrandDAO($this->DBPool);
  }

  public function createCounterRuleDAO(): DAO {
    return new CounterRuleDAO($this->DBPool);
  }

  public function createDeviceDAO(): DAO {
    return new DeviceDAO($this->DBPool);
  }

  public function createFunctionDAO(): DAO {
    return new FunctionDAO($this->DBPool);
  }

  public function createMethodDAO(): DAO {
    return new MethodDAO($this->DBPool);
  }

  public function createModelDAO(): DAO {
    return new ModelDAO($this->DBPool);
  }

  public function createParkDAO(): DAO {
    return new ParkDAO($this->DBPool);
  }

  public function createTypeDAO(): DAO {
    return new TypeDAO($this->DBPool);
  }

  public function createVisitDAO(): DAO {
    return new VisitDAO($this->DBPool);
  }

  public function createDeleteRequestDAO(): DeleteRequestDAO
    {
        return $this->createFileFinderDAO(self::DELETE_REQUEST_DAO);
    }

    public function createBusinessUnitDAO(): BusinessUnitDAO
    {
        return $this->createFileFinderDAO(self::BUSINESS_UNIT_DAO);
    }

    public function createDocumentTypeDAO(): DocumentTypeDAO
    {
        return $this->createFileFinderDAO(self::DOCUMENT_TYPE_DAO);
    }

    public function createTagDAO(): TagDAO
    {
        return $this->createFileFinderDAO(self::TAG_DAO);
    }

    public function createFileDAO(): FileDAO
    {
        return $this->createFileFinderDAO(self::FILE_DAO);
    }

    public function createTopicDAO(): TopicDAO
    {
        return $this->createFileFinderDAO(self::TOPIC_DAO);
    }

    public function createUploadDAO(): UploadDAO
    {
        return $this->createFileFinderDAO(self::UPLOAD_DAO);
    }

    public function createSearchDAO(): SearchDAO
    {
        return $this->createFileFinderDAO(self::SEARCH_DAO);
    }

    public function createFileFinderDAO($filename): DAO
    {
        return $this->createDAO('FileFinder', $filename);
    }

    public function createDAO($relativePath, $filename): DAO
    {
        $className = 'DPR\\API\\Infrastructure\\Persistence\\DAO\\';
        $className .= ($relativePath != '') ? ($relativePath . '\\') : '';
        $className .= $filename;

        return new $className($this->DBPool);
    }
}
