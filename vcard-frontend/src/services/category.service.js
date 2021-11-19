import axios from 'axios';
import authHeader from './auth-header';

class CategoryService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  async getCategoryBoard(id, link=`vcards/${id}/categories?page=1`) {
    return await axios.get(link, { headers: authHeader() });
  }

  getCategory(id){
    return axios.get('categories/' + id, { headers: authHeader() });
  }

  postCategory(category){
    return axios.post('categories',category,     { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new CategoryService();