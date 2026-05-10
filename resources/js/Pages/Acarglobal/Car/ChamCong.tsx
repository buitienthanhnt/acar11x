import { Head, router, useForm, usePage } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout"
import { PlusCircleIcon, TrashIcon, XCircleIcon } from "@heroicons/react/24/solid";
import { useCallback, useState } from "react";
import Modal from "@/Components/Modal";
import TextInputField from "../components/form/TextInputField";
import PrimaryButton from "@/Components/PrimaryButton";
import { EmployeeType } from "../types/EmployeeType";
import Checkbox from "@/Components/Checkbox";
import TextInput from "@/Components/TextInput";

const ChamCong = ({ employess, date, workTimes }: any) => {
  const [showForm, setShowForm] = useState(false);

  return (
    <ContentLayout>
      <Head title="chấm công"></Head>
      <div className="p-4 bg-blue-gray-100 flex-1">
        <EmployeeMap employess={employess}></EmployeeMap>
        <PlusCircleIcon
          className="w-10 h-10 absolute l-10 bottom-10"
          title="thêm nhân viên"
          onClick={() => {
            setShowForm(true)
          }}></PlusCircleIcon>
        <Modal
          show={showForm}
        >
          <div className="flex justify-between items-center pr-5 p-2">
            <h2 className="font-semibold text-xl">Thêm nhân viên</h2>
            <XCircleIcon className="w-10 h-10" onClick={() => {
              setShowForm(false)
            }}></XCircleIcon>
          </div>
          <EmployeeForm>
          </EmployeeForm>
        </Modal>
      </div>

    </ContentLayout >
  )
}

const EmployeeMap = ({ employess }: { employess: EmployeeType[] }) => {
  const { props: { workTimes: { time_work }, date } } = usePage();
  const [selectedEmployee, setSelectedEmployee] = useState<{ id: number, time: number }[]>(time_work || []);

  const onSelectEmployee = useCallback(({ id, time }: { id: number, time: number }) => {
    const value = selectedEmployee.find((e) => e.id === id);
    if (!value) {
      setSelectedEmployee((prev) => [...prev, { id, time }])
      return;
    }

    if (value.time === time) {
      setSelectedEmployee((prev) => [...prev.filter((e) => e.id !== id)]);
      return;
    }

    setSelectedEmployee((prev) => [...prev.filter((e) => e.id !== id), { id, time }]);

  }, [selectedEmployee]);

  const onSelectAll = useCallback(() => {
    if (selectedEmployee.length === employess.length) {
      setSelectedEmployee([]);
      return;
    }

    setSelectedEmployee(employess.map((e) => ({ id: e.id, time: 8 })))
  }, [employess, selectedEmployee])

  /**
   * save log
   */
  const onSaveLogWork = useCallback(() => {
    const today = new Date(date);
    router.post('/acar/save-work-time', {
      time_work: selectedEmployee,
      date: today.getFullYear() + '-' +
        (today.getMonth() + 1 > 9 ? today.getMonth() + 1 : '0' + (today.getMonth() + 1)) + '-' +
        (today.getDate() > 9 ? today.getDate() : '0' + today.getDate()),
    });
  }, [selectedEmployee, date])

  if (!employess) {
    return null;
  }

  return (
    <>
      <div className="space-y-2">
        <div className="bg-gray-400 p-2 rounded-md flex">
          <div className="flex-1 flex-col">
            <span className="font-semibold text-xl">Ngày: </span> <input type="date" className="w-1/3 p-3 rounded-md" value={date} onChange={(e) => {
              router.visit('/acar/cham-cong', {
                data: {
                  date: e.target.value
                }
              })
            }}></input>
          </div>
          <div className="flex gap-4 items-center">
            <Checkbox className="size-5"
              checked={selectedEmployee.length === employess.length}
              onChange={onSelectAll}></Checkbox>
            <div className="size-5"></div>
          </div>
        </div>
        {employess.map((employee: any) => <Employee selectedEmployee={selectedEmployee} onSelectEmployee={onSelectEmployee} key={employee.id} {...employee}></Employee>)}
        <div className="flex justify-between">
          <PrimaryButton onClick={() => router.visit('/acar/work-history')} >Lịch sử chấm công</PrimaryButton>
          <PrimaryButton onClick={onSaveLogWork} >Lưu chấm công</PrimaryButton>
        </div>
      </div>
    </>
  )
}

const Employee = ({ id, name, status, phone, email, address, onSelectEmployee, selectedEmployee }: EmployeeType & { onSelectEmployee: ({ id, time }: { id: number, time: number }) => void, selectedEmployee: { id: number, time: number }[] }) => {
  const checked = selectedEmployee.find((e) => e.id === id);
  const onChecked = () => {
    onSelectEmployee({
      id: id,
      time: checked?.time || 8,
    });
  }

  return (
    <div className="bg-gray-400 p-2 rounded-md flex">
      <div className="flex-1 flex-col">
        <p className="">{name}</p>
        <p className="">{status}</p>
      </div>
      <div className="flex gap-4 items-center">
        {checked && <>
          <TextInput
            type={'number'}
            min={0}
            max={24}
            step={0.5}
            className="w-20"
            defaultValue={checked ? checked.time : 8}
            onChange={(e) => onSelectEmployee({ id, time: Number(e.target.value) })}
          >
          </TextInput>
          <span>Giờ</span></>}
        <Checkbox className="size-5" checked={!!checked} onChange={onChecked}></Checkbox>
        <TrashIcon className="size-5"></TrashIcon>
      </div>
    </div>
  )
}

const EmployeeForm = () => {
  const { data, setData, post, processing, errors, reset, recentlySuccessful, setError } = useForm(
    {
      name: '',
      phone: '',
      email: '',
      address: '',
      status: '',
      date_s: '',
    }
  );

  const onRegisterEmployee = useCallback(async () => {
    if (!data.name) {
      setError('name', 'Tên nhân viên là bắt buộc');
      return;
    }

    if (!data.email) {
      setError('email', 'Email is required');
      return;
    }

    const email = data.email;
    post('/acar/add-employee', {
      onSuccess: (response) => {

      }
    })
    // const response = await axios.post('/acar/add-employee', data);
    // console.log(response.data);
  }, [data])

  return (
    <div className="p-4 space-y-1">
      <TextInputField
        type="text"
        label="Tên nhân viên"
        error={errors.name}
        onChange={e => setData('name', e.target.value)}
        // className="w-full"
        placeholder="Tên nhân viên">
      </TextInputField>
      <TextInputField
        type="tel"
        label="Số điện thoại"
        onChange={e => setData('phone', e.target.value)}
        placeholder="Số điện thoại">
      </TextInputField>
      <TextInputField
        type="email"
        label="Email"
        error={errors.email}
        onChange={e => setData('email', e.target.value)}
        placeholder="email">
      </TextInputField>
      <TextInputField
        type="text"
        label="Địa chỉ"
        onChange={e => setData('address', e.target.value)}
        placeholder="địa chỉ">
      </TextInputField>
      <TextInputField
        list="browserx"
        type="text"
        label="Vị trí công tác"
        onChange={e => setData('status', e.target.value)}
        placeholder="vị trí công tác">
      </TextInputField>
      <datalist id="browserx">
        <option value="Giám đốc" />
        <option value="Phó giám đốc" />
        <option value="Quản lý" />
        <option value="Thợ chính" />
        <option value="Thợ phụ" />
        <option value="Nhân viên hỗ trợ" />
        <option value="Kế toán" />
        <option value="Thủ kho" />
        <option value="An ninh" />
        <option value="Kỹ thuật viên" />
        <option value="Nhân viên" />
        <option value="Chăm sóc khách hàng" />
        <option value="Cố vấn dịch vụ" />
        <option value="Vận chuyển" />
        <option value="Điều hành" />
        <option value="Kinh doanh" />
      </datalist>
      <TextInputField
        type="number"
        min={0}
        label="Ngày công(nghìn vnđ/ngày)"
        onChange={e => setData('date_s', e.target.value)}
        placeholder="Ngày công">
      </TextInputField>
      <div className="flex justify-end">
        <PrimaryButton onClick={onRegisterEmployee} >Lưu nhân viên</PrimaryButton>
      </div>
    </div>
  )
}

export default ChamCong;