import axios from 'axios';
import authHeader from './auth-header';

class StatisticsService {
  async getFinancial(options) {
      return await axios.get('statistics/financial'+options, { headers: authHeader() })
  }
  async getBalancePerTime(options) {
    return await axios.get('statistics/balancepertime'+options, { headers: authHeader() })
  }
  async getTotalSpent(){
    return await axios.get('statistics/totalspent', { headers: authHeader() })
  }
  async getTotalRecieved(){
    return await axios.get('statistics/totalrecieved', { headers: authHeader() })
  }
}

export default new StatisticsService();