import AuthService from './api/AuthService';
import DeviceService from './api/DeviceService';
import BrandService from  './api/BrandService';
import CounterRulesService from  './api/CounterRulesService';
import FunctionService from  './api/FunctionService';
import MethodService from  './api/MethodService';
import ModelService from  './api/ModelService';
import ParkService from  './api/ParkService';
import TypeService from  './api/TypeService';
import UserService from  './api/UserService';
import VisitService from  './api/VisitService';



const APIClient = {
    Auth: AuthService,
    Devices: DeviceService,
    Parks: ParkService,
    Brands: BrandService,
    CounterRules: CounterRulesService,
    Functions: FunctionService,
    Methods: MethodService,
    Models: ModelService,
    Types: TypeService,
    Users: UserService,
    Visits: VisitService
}

export default APIClient;