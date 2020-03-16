export const state = () => ({
  exam: []
})

export const mutations = {
  setExam (state, exam) {
    state.exam = exam
  }
}

export const actions = {
  fetchExam ({ commit }) {
    if (process.client) {
      const exam = JSON.parse(localStorage.getItem('exam'))
      commit('setExam', exam)
    }
  },
  storeExam ({ commit }, { exam }) {
    if (process.client) {
      localStorage.setItem('exam', JSON.stringify(exam))
      commit('setExam', exam)
    }
  }
}
