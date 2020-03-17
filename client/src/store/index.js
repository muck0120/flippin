import cookieparser from 'cookieparser'

export const actions = {
  async nuxtServerInit ({ commit }, { req }) {
    try {
      const token = cookieparser.parse(req.headers.cookie).token || null
      commit('user/setToken', token)
      const { data } = await this.$axios.get('/users/profile')
      commit('user/setUser', data)
    } catch (e) {
      console.log('guest')
    }
  }
}
