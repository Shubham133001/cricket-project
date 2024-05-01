const showToast = ({ state, commit }, message) => {
  if (state.toast.show) commit('hideToast')

  setTimeout(() => {
    commit('showToast', {
      color: 'black',
      message,
      timeout: 3000
    })
  })
}

const showError = ({ state, commit }, { message = 'Failed!', error }) => {
  if (state.toast.show) commit('hideToast')

  setTimeout(() => {
    commit('showToast', {
      color: 'error',
      message: message + ' ' + error.message,
      timeout: 10000
    })
  })
}

const showSuccess = ({ state, commit }, message) => {
  if (state.toast.show) commit('hideToast')

  setTimeout(() => {
    commit('showToast', {
      color: 'success',
      message,
      timeout: 3000
    })
  })
}

const getStoreData = ({ commit }) => {
  return new Promise((resolve, reject) => {
    axios.get('/api/store')
      .then(response => {
        commit('setStoreDetails', response.data.storeDetails)
        localStorage.setItem('store', JSON.stringify(response.data.storeDetails))
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getSmtpData = ({ commit }) => {
  return new Promise((resolve, reject) => {
    axios.get('/api/smtp')
      .then(response => {
        commit('setSmtpDetails', response.data.smtpDetails)
        localStorage.setItem('smtp', JSON.stringify(response.data.smtpDetails))
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getSmsData = ({ commit }) => {
  return new Promise((resolve, reject) => {
    axios.get('/api/sms')
      .then(response => {
        commit('setSmsDetails', response.data.smsDetails)
        localStorage.setItem('sms', JSON.stringify(response.data.smsDetails))
        resolve(response)
      })
      .catch(error => {
        reject(error)
      })
  })
}

const getUserLogin = ({ commit }) => {
  if (localStorage.getItem('userdetails')) {
    commit('setIsUserlogin', true);
    return true
  }
}


export default {
  showToast,
  showError,
  showSuccess,
  getStoreData,
  getSmtpData,
  getSmsData,
  getUserLogin
}
