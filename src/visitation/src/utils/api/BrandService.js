import HTTPClient from './HTTPClient';

export default class BrandService extends HTTPClient {
    static getBrands() {
        return BrandService.get(`/visitation/brands`).then(response => response.brands);
    }

    static getBrand(brandId) {
        return BrandService.get(`/visitation/brands/${brandId}`).then(response => response.brand);
    }

    static createBrand(brand) {
        return BrandService.post(`/visitation/brands`, brand).then(response => response.brand);
    }

    static updateBrand(brandId, brand) {
        return BrandService.put(`/visitation/brands/${brandId}`, brand);
    }

    static deleteBrand(brandId) {
        return BrandService.delete(`/visitation/brands/${brandId}`);
    }
}