export default function ({ store, route, redirect }) {
  if (!store.state.user.user) {
    const from = encodeURIComponent(route.fullPath)
    return redirect(`/signin?redirect=${from}`)
  }
}
