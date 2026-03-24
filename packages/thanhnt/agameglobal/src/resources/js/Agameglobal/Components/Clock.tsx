import { FunctionComponent, useCallback, useEffect, useState } from "react";
import { formatTimeNumberToString } from "../Helper/TimeHelper";

type Props = {
    start: number;
    type: 'up' | 'down',
    autoRun?: boolean;
    label?: string,
    onFinish?: () => void,
    onToggle?: (time: number) => void,
};

const Clock: FunctionComponent<Props> = ({ start, autoRun, type, label = '', onFinish, onToggle }) => {
    const [time, setTime] = useState<number>(start || 0);

    const onprogress = useCallback(() => {
        onToggle?.(time)
    }, [time, autoRun])

    useEffect(() => {
        if (!autoRun) {
            return;
        }

        if (type === 'down' && time === 0) {
            onFinish?.();
            return;
        }

        const onRun = setTimeout(() => {
            setTime(prev => type === 'up' ? prev + 1 : prev - 1);
        }, 100);
    }, [type, autoRun, time])

    return (
        <div className={`${time < 50 && type === 'down' ? 'bg-red-300' : autoRun ? 'bg-purple-300' : 'bg-blue-300'} p-2 rounded-md flex flex-1 flex-col w-full justify-center items-center content-center`} onClick={onprogress}>
            <p>{label}</p>
            <p className={`bg-green-500 px-2 rounded-md ${time === 0 ? 'w-auto' : 'w-16'} text-white`}>{time === 0 && type === 'down' ? 'kết thúc' : formatTimeNumberToString(time, true)}</p>
        </div>
    )
}

export default Clock;
