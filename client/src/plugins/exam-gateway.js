export default ({ store, error }, inject) => {
  const examGateway = () => {
    if (process.server) return false
    store.dispatch('exam/fetchExam')

    let originIds = store.state.card.cards.map(card => card.card_id)
    let storedIds = store.state.exam.exam.map(card => card.cardId)
    originIds = originIds.filter((id, index, self) => {
      return self.indexOf(id) === index
    })
    storedIds = storedIds.filter((id, index, self) => {
      return self.indexOf(id) === index
    })

    const originContainsStored = originIds.every(id => {
      return storedIds.indexOf(id) !== -1
    })
    const storedContainsOrigin = storedIds.every(id => {
      return originIds.indexOf(id) !== -1
    })

    if (!originContainsStored || !storedContainsOrigin) {
      error(400, 'Bad Request')
    }
  }
  inject('examGateway', examGateway)
}
