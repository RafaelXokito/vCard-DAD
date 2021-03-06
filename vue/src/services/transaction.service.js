import axios from 'axios';
import authHeader from './auth-header';

class TransactionService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  async getTransactionBoard(link='transactions?page=1') {
    return await axios.get(link, { headers: authHeader() });
  }

  async getLastTransaction(link='lasttransaction') {
    return await axios.get(link, { headers: authHeader() });
  }

  getTransaction(id){
    return axios.get('transactions/' + id, { headers: authHeader() });
  }

  postTransaction(transaction){
    return axios.post('transactions',transaction, { headers: authHeader() })
  }

  patchTransaction(transaction){
    return axios.patch(`transactions/${transaction.id}`,transaction, { headers: authHeader() })
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new TransactionService();