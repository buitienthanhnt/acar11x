import { useCallback, useEffect, useState } from "react";
import { formatTimeNumberToString } from "./Helper/TimeHelper";
import { ArrowPathIcon, BugAntIcon, FlagIcon, PlayCircleIcon, StopCircleIcon } from "@heroicons/react/24/solid";
import { Head } from "@inertiajs/react";

export default function BamGioDon() {
    /**
     * time: tính bằng 1/10 giây, vd: 3881 = 388.1s = 6p28s
     */
    const [time, setTime] = useState<number>(0);
    const [breaks, setBreaks] = useState<number[]>([]);
    const [showTenths, setShowTenths] = useState<boolean>(false);
    const [isRunning, setIsRunning] = useState<boolean>(false);

    const handleReset = useCallback(() => {
        setIsRunning(false);
        setTime(0);
        setBreaks([]);
    }, []);

    const handleBreak = useCallback(() => {
        setBreaks(prevBreaks => [...prevBreaks, time]);
    }, [time]);

    useEffect(() => {
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

    return (
        <div className="flex-1 bg-blue-gray-300 min-h-screen p-2 container mx-auto flex flex-col gap-4">
            <Head title="bam gio"></Head>
            <h1 className="text-2xl font-bold text-white">Đồng hồ bấm giờ:</h1>
            <div className="p-4 rounded-md flex gap-2 justify-between relative bg-gradient-to-r from-blue-gray-200 to-blue-gray-100 h-96">
                <div className="items-center justify-center w-[120px] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 ">
                    <h3 style={{
                        letterSpacing: 1,
                    }} className="font-semibold text-lg lg:text-2xl w-full">{formatTimeNumberToString(time, showTenths, true)}</h3>
                </div>
                {/* @ts-ignore */}
                <div className={`flex absolute top-0 left-1/2 -translate-x-1/2 p-4 bg-purple-200 rounded-full text-base lg:text-lg font-semibold`} onClick={() => setIsRunning(!isRunning)}>
                    {isRunning ? <StopCircleIcon className="w-6 h-6"></StopCircleIcon> : <PlayCircleIcon className="w-6 h-6" color='rgb(144 202 249)'></PlayCircleIcon>}
                </div>
                {/* @ts-ignore */}
                <div className="flex absolute top-1/2 right-0 -translate-y-1/2 p-4 bg-blue-200 rounded-full text-base lg:text-lg font-semibold" onClick={handleBreak} variant="gradient">
                    <FlagIcon className="w-6 h-6" color='white'></FlagIcon>
                </div>
                {/* @ts-ignore */}
                <div className="flex absolute bottom-0 left-1/2 -translate-x-1/2 p-4 bg-red-200 rounded-full text-base lg:text-lg font-semibold" onClick={handleReset}>
                    <ArrowPathIcon className="w-6 h-6"></ArrowPathIcon>
                </div>
                {/* @ts-ignore */}
                <div className="flex absolute top-1/2 left-0 -translate-y-1/2 p-4 bg-green-200 rounded-full text-base lg:text-lg font-semibold" onClick={() => setShowTenths(!showTenths)}>
                    <BugAntIcon className="w-6 h-6" color={showTenths ? 'red' : ''}></BugAntIcon></div>
            </div>

            <div className="flex-1 flex flex-col h-full p-4 items-center">
                {!!breaks.length && <div className="items-center rounded-md w-full flex flex-wrap gap-2">
                    {breaks.map((breakTime, index) => {
                        return <div key={index} className="bg-blue-gray-200 font-semibold p-2 rounded-md flex flex-col items-center gap-1">
                            <p>{formatTimeNumberToString(breakTime, showTenths)}</p>
                            <p className="text-sm font-semibold text-purple-600">{breakTime / 10} ms</p>
                            <p className="text-sm font-semibold text-purple-600">{formatTimeNumberToString(index === 0 ? breaks[0] : breaks[index] - breaks[index - 1], true)}</p>
                        </div>;
                    })}
                </div>}
            </div>
        </div>
    );
}
