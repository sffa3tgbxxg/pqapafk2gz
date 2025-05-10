import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'

dayjs.extend(utc)

export function useTimer(targetDate) {
  const timeLeft = ref(0)
  let intervalId = null

  const updateTimeLeft = (target) => {
    if (!target || !target.isValid()) {
      timeLeft.value = 0
      return
    }
    const now = dayjs.utc()
    timeLeft.value = target.diff(now, 'seconds')

    if (timeLeft.value <= 0) {
      timeLeft.value = 0
      clearInterval(intervalId)
    }
  }

  const startTimer = (target) => {
    if (intervalId) clearInterval(intervalId) // Очищаем старый интервал
    if (target && target.isValid()) {
      updateTimeLeft(target)
      intervalId = setInterval(() => {
        updateTimeLeft(target)
      }, 1000)
    }
  }

  // Следим за изменениями targetDate
  watch(
    targetDate,
    (newDate) => {
      const target = newDate ? dayjs.utc(newDate) : null
      startTimer(target)
    },
    { immediate: true },
  )

  onUnmounted(() => {
    if (intervalId) clearInterval(intervalId)
  })

  const formattedTimeLeft = computed(() => {
    const hours = Math.floor(timeLeft.value / 3600)
    const minutes = Math.floor((timeLeft.value % 3600) / 60)
    const seconds = timeLeft.value % 60
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
  })

  return { formattedTimeLeft }
}
