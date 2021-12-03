import axios from 'axios';
import authHeader from './auth-header';

class StatisticsService {
  async getFinancial(options) {
      return await axios.get('statistics/financial'+options, { headers: authHeader() })
  }
}

export default new StatisticsService();