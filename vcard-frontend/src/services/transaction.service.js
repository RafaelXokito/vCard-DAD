import axios from 'axios';
import authHeader from './auth-header';

class TransactionService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  getTransactionBoard() {
    return axios.get('transactions', { headers: authHeader() });
  }

  getTransaction(id){
    return axios.get('transactions/' + id, { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new TransactionService();