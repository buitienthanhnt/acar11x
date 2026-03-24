import { useCallback, useEffect, useState } from "react";
import { formatTimeNumberToString } from "./Helper/TimeHelper";
import { ArrowPathIcon, BugAntIcon, FlagIcon, PlayCircleIcon, StopCircleIcon } from "@heroicons/react/24/solid";
import { Head } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";

const DemNguoc = () => {
    /**
      * time: tính bằng 1/10 giây, vd: 3881 = 388.1s = 6p28s
      */
    const [time, setTime] = useState<number>(0);
    const [breaks, setBreaks] = useState<number[]>([]);
    const [showTenths, setShowTenths] = useState<boolean>(false);
    const [isRunning, setIsRunning] = useState<boolean>(false);
    const [inputValue, setInputValue] = useState<number | string>('');

    const handleReset = useCallback(() => {
        setIsRunning(false);
        setTime(0);
        setBreaks([]);
        setInputValue('');
    }, []);

    const handleBreak = useCallback(() => {
        setBreaks(prevBreaks => [...prevBreaks, time]);
    }, [time]);

    const handleRunning = useCallback(() => {
        setIsRunning(prev => !prev);
    }, []);

    useEffect(() => {
        setTime(inputValue ? Number(inputValue) : 0);
    }, [inputValue]);

    useEffect(() => {
        if (isRunning) {
            // const interval = setInterval(() => {
            //   /**
            //    * time tăng lên 1 sau mỗi 100ms, tương đương với việc tăng lên 1/10 giây sau mỗi 100ms
            //    */
            //   setTime(prevTime => prevTime - 1);
            // }, 100);
            if (time > 0) {
                setTimeout(() => {
                    setTime(prevTime => prevTime - 1);
                }, 100);
            } else {
                setIsRunning(false);
            }
        }
    }, [isRunning, time]);

    return (
        <div className={`flex-1 bg-blue-gray-300 min-h-screen p-2 container mx-auto flex flex-col gap-4`}>
            <Head title="Đếm ngược" />
            <h1 className="text-2xl font-bold text-white">Đồng hồ đếm ngược:</h1>
            <div className={`p-4 rounded-md flex gap-2 justify-between relative bg-gradient-to-r ${time >= 50 ? 'from-blue-gray-200 to-blue-gray-100' : time === 0 ? 'from-red-200 to-red-100' : 'from-orange-200 to-orange-100'} h-96`}>
                <div className="items-center justify-center w-72 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 ">
                    {/* @ts-ignore */}
                    <TextInput type="number" className='translate-x-1/4' onChange={(e: any) => setInputValue(e.target.value)} value={inputValue} disabled={isRunning} placeholder={'Input time start (ms)'} />
                    <h3 style={{
                        letterSpacing: 1,
                    }} className="font-semibold text-lg lg:text-2xl w-[120px] mt-2 translate-x-24">{formatTimeNumberToString(time, showTenths, true)}
                    </h3>
                </div>
                {/* @ts-ignore */}
                {time > 0 && <div className={`flex absolute top-0 left-1/2 -translate-x-1/2 p-4 bg-purple-200 rounded-full text-base lg:text-lg font-semibold`} onClick={handleRunning}>
                    {isRunning ? <StopCircleIcon className="w-6 h-6"></StopCircleIcon> : <PlayCircleIcon className="w-6 h-6" color='rgb(144 202 249)'></PlayCircleIcon>}
                </div>}
                {/* @ts-ignore */}
                {time > 0 && <div className="flex absolute top-1/2 right-0 -translate-y-1/2 p-4 bg-blue-200 rounded-full text-base lg:text-lg font-semibold" onClick={handleBreak} variant="gradient">
                    <FlagIcon className="w-6 h-6" color='white'></FlagIcon>
                </div>}
                {/* @ts-ignore */}
                <div className="flex absolute bottom-0 left-1/2 -translate-x-1/2 p-4 bg-red-200 rounded-full text-base lg:text-lg font-semibold" onClick={handleReset}>
                    <ArrowPathIcon className="w-6 h-6"></ArrowPathIcon>
                </div>
                {/* @ts-ignore */}
                <div className="flex absolute top-1/2 left-0 -translate-y-1/2 p-4 bg-green-200 rounded-full text-base lg:text-lg font-semibold" onClick={() => setShowTenths(!showTenths)}>
                    <BugAntIcon className="w-6 h-6" color={showTenths ? 'red' : ''}></BugAntIcon>
                </div>
            </div>

            <div className="flex-1 flex flex-col h-full p-4 items-center">
                {!!breaks.length && <div className="items-center rounded-md w-full flex flex-wrap gap-2">
                    {breaks.map((breakTime, index) => {
                        return <div key={index} className="bg-blue-gray-200 font-semibold p-2 rounded-md flex flex-col items-center gap-1">
                            <p>{formatTimeNumberToString(breakTime)} s</p>
                            <p className="text-sm font-semibold text-purple-600">{breakTime / 10} ms</p>
                        </div>;
                    })}
                </div>}
            </div>
        </div>
    )
}

export default DemNguoc;
