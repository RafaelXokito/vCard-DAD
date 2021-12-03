import axios from 'axios';
import authHeader from './auth-header';

class DefaultCategoryService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  async getDefaultCategoryBoard(link=`/defaultcategories?page=1`) {
    return await axios.get(link, { headers: authHeader() });
  }

  getDefaultCategory(id){
    return axios.get('defaultcategories/' + id, { headers: authHeader() });
  }

  postDefaultCategory(defaultcategory){
    return axios.post('defaultcategories',defaultcategory, { headers: authHeader() });
  }

  patchDefaultCategory(defaultcategory){
    return axios.patch(`defaultcategories/${defaultcategory.id}`,defaultcategory, { headers: authHeader() });
  }

  deleteDefaultCategory(defaultcategory){
    return axios.delete(`defaultcategories/${defaultcategory.id}`, { headers: authHeader() });
  }

  restoreDefaultCategory(defaultcategory){
    return axios.post(`defaultcategories/${defaultcategory.id}/restore`, {}, { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new DefaultCategoryService();