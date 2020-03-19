export default ({ store, $axios }) => {
  $axios.onRequest(config => {
    const token = store.state.user.token
    config.headers.common['Authorization'] = token ? `Bearer ${token}` : ''
    config.headers.common['Accept'] = 'application/json'
  })
}
