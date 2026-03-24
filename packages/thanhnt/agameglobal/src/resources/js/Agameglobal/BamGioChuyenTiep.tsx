import { Head, useForm } from "@inertiajs/react"
import BasePage from "./Layouts/BasePage";
import React, { RefObject, useRef } from "react";
import { useCallback, useState, } from "react";
import Clock from "./Components/Clock";
import {
    Button,
    Dialog,
    DialogHeader,
    DialogBody,
    DialogFooter,
    Input,
    Typography,
} from "@material-tailwind/react";
import _ from "lodash";
import { PlayCircleIcon, StopCircleIcon } from "@heroicons/react/24/solid";

const BamGioChuyenTiep = () => {
  
    const [user, setUser] = useState<{ name: string, time?: number }[]>([]);
    const [downTime, setDownTime] = useState<number>(0);
    const [active, setActive] = useState<number>(0);
    const history: RefObject<{ name: string, time?: number, step: number }[]> = useRef([]);
    const [open, setOpen] = React.useState(false);

    const saveHistory = useCallback((time: number) => {
        /**
        * save history time before change active user
        */
        const currentUser = active - 1;
        const currentHistory = history.current;
        const maxStep = _.maxBy(currentHistory, h => h.step)?.step || 0;
        currentHistory[currentUser] = {
            name: user[currentUser].name,
            time: time,
            step: maxStep + 1,
        };
        // set value for ref
        history.current = currentHistory;
    }, [active, user])

    const onChangeUser = useCallback((time: number) => {
        saveHistory(time);
        /**
         * change user active
         */
        setActive(prev => prev === user.length ? 1 : prev + 1)
    }, [user, saveHistory])

    const onAddUser = (newUser: { name: string, downTime?: number }) => {
        setUser(prev => [...prev, {name: newUser.name, }]);
        setDownTime(newUser.downTime || 0);
    }

    const saveHistoryStorage = () => {
        console.log('????', history.current);
    }

    const onPause = useCallback(() => {
        if (user.length === 0) {
            return;
        }
        if (active !== 0) {
            setActive(0);
            return;
        }

        const _history = history.current;
        const lastStep = _.maxBy(_history, h => h.step);
        if (lastStep) {
            const lastIndex = _history.indexOf(lastStep) + 1;
            setActive(lastIndex < user.length ? lastIndex + 1 : 1)
        } else {
            setActive(1);
        }
    }, [active, user])

    return (
        <BasePage>
            <Head title="bấm giờ"></Head>
            <h1 className="text-2xl font-bold text-orange-300">Đồng hồ đếm chuyển tiếp:</h1>
            <div className="space-y-4 flex-1 flex flex-col">
                <div className="flex gap-2 justify-between">
                    {/* @ts-ignore */}
                   {!active &&  <Button onClick={() => setOpen(!open)}>
                        add user
                    </Button>}
                    {/* @ts-ignore */}
                    {user.length > 0 && <Button onClick={() => {
                        setUser([]);
                        setActive(0);
                        history.current = [];
                    }} color="orange">
                        reset
                    </Button>}
                    {/* @ts-ignore */}
                    {!!user.length && <Button onClick={onPause} color={!!active ? 'red' : 'green'}>
                        {active ? <StopCircleIcon color="white" className="w-6 h-6"></StopCircleIcon> : <PlayCircleIcon className="w-6 h-6" color='white'></PlayCircleIcon>}
                    </Button>}
                    {/* @ts-ignore */}
                    {/* {!!user.length && <Button onClick={saveHistoryStorage} color="blue">
                        Save
                    </Button>} */}
                </div>
                <div className={` bottom-2 absolute gap-2 flex flex-1`}>
                    {!!user && user.map((item, index) => <ClockItem key={index + 1} com={item} onToggle={onChangeUser} active={active === index + 1} downTime={downTime}></ClockItem>)}
                </div>
                <div className="justify-center items-center flex flex-1 rounded-md bg-blue-200" onClick={()=>{
                    if (user.length === 0) {
                      return;
                    }
                     setActive(prev => prev === user.length ? 1 : prev + 1)
                }}>
                  <p>active user: {user[active - 1]?.name || 'undefined'}</p>                
                </div>
            </div>
            <UserForm open={open} handleOpen={() => setOpen(!open)} onSave={onAddUser}></UserForm>
        </BasePage>
    )
}

const ClockItem = ({ com, onToggle, active, downTime }: { com: { name: string }, onToggle: (time: number) => void, active: boolean, downTime?: number }) => {
    const onPress = useCallback((time: number) => {
        onToggle(time);
    }, [onToggle])

    return (
        <div className={`flex flex-col flex-1 h-24 rounded-md justify-center items-center bg-blue-gray-500 ${active ? 'border-2 border-gray-700' : ''}`}>
            <Clock start={downTime || 0} autoRun={!!active} type={downTime ? 'down' : 'up'} label={com.name} onToggle={onPress}></Clock>
        </div>
    )
}

const UserForm = ({ open, handleOpen, onSave }: { open: boolean, handleOpen: () => void, onSave: (user: { name: string }) => void }) => {
    const { data, setData } = useForm({
        name: '',
        downTime: 0,
    })

    const saveUser = useCallback(() => {
        if (!data.name) {
            return;
        }
        onSave(data)
    }, [data])

    return ( // @ts-ignore
        <Dialog open={open} size="xs" handler={handleOpen}>
            <div className="flex items-center justify-between">
                {/* @ts-ignore */}
                <DialogHeader className="flex flex-col items-start">
                    {/* @ts-ignore */}
                    <Typography className="mb-1" variant="h4">Thêm đối tượng chuyển tiếp</Typography>
                </DialogHeader>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    className="mr-3 h-5 w-5"
                    onClick={handleOpen}
                >
                    <path
                        fillRule="evenodd"
                        d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                        clipRule="evenodd"
                    />
                </svg>
            </div>
            {/* @ts-ignore */}
            <DialogBody>
                <div className="grid gap-6">
                    {/* @ts-ignore */}
                    <Typography className="-mb-1" color="blue-gray" variant="h6">
                        Tên đại diện:
                    </Typography>
                    {/* @ts-ignore */}
                    <Input label="Username" name="name" required onChange={e => setData('name', e.target.value)} />
                    {/* @ts-ignore */}
                    <Input label="Thời gian đếm ngược(áp dụng cho tất cả user)" name="downTime" type="number" onChange={e => setData('downTime', parseInt(e.target.value) || 0)} />
                </div>
            </DialogBody>
            {/* @ts-ignore */}
            <DialogFooter className="space-x-2">
                {/* @ts-ignore */}
                <Button variant="gradient" color="gray" onClick={saveUser}>
                    Lưu
                </Button>
            </DialogFooter>
        </Dialog>
    )
}

export default BamGioChuyenTiep;
