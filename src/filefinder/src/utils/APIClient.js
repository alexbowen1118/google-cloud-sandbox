import UploadFileService from './api/UploadFileService'
import FilterButtonService from './api/FilterButtonService'
import SearchService from './api/SearchService';
import TopicViewService from './api/TopicViewService';
import AdminActionsService from './api/AdminActionsService';



const APIClient = {
    UploadFile: UploadFileService,
    FilterButton: FilterButtonService,
    Search: SearchService,
    RelatedTopicFiles: TopicViewService,
    AdminActions: AdminActionsService,
};


export default APIClient;