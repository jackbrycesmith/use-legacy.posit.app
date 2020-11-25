export function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms))
}

export async function sleepToEnsureMinTimeSpent(performanceTimeBefore, minTimeMs) {
  const timeTakenMs = performance.now() - performanceTimeBefore

  if (timeTakenMs < minTimeMs) {
    await sleep(minTimeMs - timeTakenMs)
  }
}
