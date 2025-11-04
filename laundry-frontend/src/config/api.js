// API Configuration
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000';

export const API_URL = API_BASE_URL;
export const API_ENDPOINT = `${API_BASE_URL}/api`;

export default API_ENDPOINT;

