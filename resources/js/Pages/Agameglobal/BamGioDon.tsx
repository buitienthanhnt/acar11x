import { Button } from "@material-tailwind/react";
import { useCallback, useEffect, useState } from "react";

export default function BamGioDon() {
  /**
   * time: tính bằng 1/10 giây, vd: 3881 = 388.1s = 6p28s
   */
  const [time, setTime] = useState<number>(0);
  const [showTenths, setShowTenths] = useState<boolean>(false);
  const [isRunning, setIsRunning] = useState<boolean>(false);

  const handleReset = useCallback(() => {
    setTime(0);
  }, []);

  useEffect(()=>{
   if (isRunning) {
     const interval = setInterval(() => {
      /**
       * time tăng lên 1 sau mỗi 100ms, tương đương với việc tăng lên 1/10 giây sau mỗi 100ms 
       */
      setTime(prevTime => prevTime + 1);
    }, 100);
    return () => clearInterval(interval);
   }
  }, [isRunning]);

    const hours = Math.floor(time / 36000);
    const minutes = Math.floor((time % 36000) / 600);
    const seconds =  Math.floor(time % 600 / 10);
    const tenths = time % 10;
    const timeInSeconds = `${hours > 9 ? hours : `0${hours}`}:${minutes > 9 ? minutes : `0${minutes}` }:${seconds < 10 ? `0${seconds}` : seconds}${showTenths ? `:0${tenths}` : ''}`;
    
  return (
    <div className="flex-1 bg-blue-gray-300 min-h-screen p-4 mx-auto flex flex-col gap-4">
      <h1>BamgioDon Component</h1>
      <div className="bg-green-400 p-4 rounded-md flex gap-2 justify-between">
        <h3>bam gio</h3>
        {/* @ts-ignore */}
        <Button onClick={handleReset} color="blue" variant="filled">Reset</Button>
         {/* @ts-ignore */}
        <Button color="purple" onClick={() => setIsRunning(!isRunning)}  variant="filled">{isRunning ? "Stop" : "Start"}</Button>
        <Button color="red" onClick={() => setShowTenths(!showTenths)} variant="filled">{showTenths ? "Hide Tenths" : "Show Tenths"}</Button>
      </div>

      <div className="flex-1 flex flex-col h-full p-4  items-center" style={{
        height: '100%',
      }}>
          <h3 className="font-semibold text-lg">time: {timeInSeconds}</h3>
      </div>
    </div>
  );
}