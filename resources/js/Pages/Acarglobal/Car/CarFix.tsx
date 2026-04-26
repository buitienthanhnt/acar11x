import React, { FunctionComponent, useCallback, useMemo } from "react";
import ContentLayout from "../Layout/ContentLayout";
import { Activity, CarFix as CarFixType } from "../types/CarType";
import { PlusCircleIcon, TrashIcon } from "@heroicons/react/24/solid";
import TextInputField from "../components/form/TextInputField";
import { Head, Link, router, useForm } from "@inertiajs/react";
import { formatPrice } from "@/Helper/StringHelper";
import { Step, Stepper } from "@material-tailwind/react";

const CarFix: FunctionComponent<{ carFix: CarFixType }> = ({ carFix }) => {

  return (
    <div className="container mx-auto bg-white flex-1 p-1 space-y-2">
      <Head title="thông tin xe"></Head>
      <CarFixInfo {...carFix}></CarFixInfo>
      <ActivityForm {...carFix}></ActivityForm>
      <ActivityList activities={carFix.activities}></ActivityList>
      <CarFixStatus {...carFix}></CarFixStatus>
      <div className="flex justify-between p-2">
        <Link href={'/acar/lenh-sua-chua/' + carFix.id} className="bg-gray-800 p-2 px-4 rounded-md text-white">Lệnh sửa chữa</Link>
        <Link href={'/acar/hoa-don/' + carFix.id} className="bg-gray-900 p-2 px-4 rounded-md text-white">Báo giá</Link>
      </div>
    </div>
  );
}

CarFix.layout = (page: React.ReactNode) => <ContentLayout children={page} />

export default CarFix;

export const CarFixInfo = ({ car, status, status_label }: CarFixType) => {
  return (
    <div className="flex bg-gray-300 p-2 rounded-md">
      <div className="flex flex-col flex-1">
        <b>Thông tin khách hàng:</b>
        <b>Khách hàng: {car.customer}</b>
        <b>Số điện thoại: {car.phone}</b>
        <i>Địa chỉ: {car.address}</i>
      </div>
      <div className="flex flex-col flex-1">
        <b>Thông tin xe:</b>
        <b>Biển số: {car.key}</b>
        <b>Loại: {car.suspension}-{car.type}</b>
        <b>VIN: {car.vin}</b>
        <b className="text-purple-700">Trạng thái: {status_label}</b>
      </div>
    </div>
  )
}

const ActivityForm = ({ id, car }: CarFixType) => {
  const { data, setData, post, processing, errors, reset, recentlySuccessful } = useForm({
    title: '',
    price: 0,
    qty: 1,
    workNow: false,
    car_fix_id: id,
    status: 'wait',
    car_id: car.id,
  });

  /**
   * add activity
   */
  const onAddItem = () => {
    if (data.title) {
      post('/acar/add-activity', {
        preserveState: false,
      });
    }
  }

  return (
    <div className="flex flex-col p-2 gap-2 relative bg-gray-300 rounded-lg">
      <div className="flex justify-center font-semibold text-xl">
        Khai báo công việc và báo giá
      </div>
      <div className="flex space-x-2 items-end">
        <div className="flex flex-1 justify-center flex-col">
          <b>Nội dung:</b>
          <textarea rows={4} onChange={e => setData('title', e.target.value)} placeholder="nội dung thực hiện" className="w-full rounded-md overflow-hidden border-gray-800 bg-transparent"></textarea>
        </div>

        <div className="flex flex-1 flex-col gap-1">
          <div className="flex flex-1 justify-center flex-col">
            <b>Chi phí(đơn vị nghìn vnđ):</b>
            <TextInputField type="number" onChange={e => setData('price', e.target.value)} min={0} className="w-full" placeholder="chi phí"></TextInputField>
          </div>

          <div className="flex flex-1 justify-center flex-col">
            <b>Số lượng:</b>
            <TextInputField type="number" min={1} onChange={e => setData('qty', e.target.value)} className="w-full" placeholder="số lượng"></TextInputField>
          </div>
        </div>
      </div>
      <div className="flex justify-end ">
        {data.title && <div className="flex font-semibold absolute left-1">
          {data.title}: {formatPrice(data.price)} X {data.qty} = {formatPrice(data.price * data.qty)}
        </div>}
        {<PlusCircleIcon title="thêm hạng mục" onClick={onAddItem} className="size-14 text-gray-500 hover:text-gray-900"></PlusCircleIcon>}
      </div>
    </div>
  )
}

const ActivityList = ({ activities }: { activities: Activity[] }) => {
  const totalPrice = useMemo(() => {
    return activities.reduce((total, activity) => total + activity.price * activity.qty, 0);
  }, [])

  if (!activities.length) {
    return null;
  }

  return (
    <div className="flex flex-col p-2 gap-2 relative bg-gray-300 rounded-lg">
      <div className="flex justify-center font-semibold text-xl">
        Chi tiết công việc và báo giá
      </div>
      <div className="space-y-2">
        {activities.map((activity, index) => {
          return <div key={index} className="flex justify-between border-b border-gray-900 py-1">
            <p className="text-gray-900 font-semibold">[{index + 1}]. {activity.title}: {formatPrice(activity.price)} X {activity.qty} =</p>

            <div className="flex space-x-2">
              <p>{formatPrice(activity.price * activity.qty)}</p>
              <Link href={'/acar/remove-activity/' + activity.id} method="delete"
                as="button" // cần có method=delete và as=button và type=button trên link này thì mới dùng được cho các method như post,delete
                onBefore={() => confirm('Bạn có chắc chắn muốn xóa không?')}
                // preserveState={true}
                preserveScroll={true}
                type="button">
                <TrashIcon className="size-5 hover:text-red-900" color={'black'}></TrashIcon>
              </Link>
            </div>
          </div>
        })}
      </div>
      <div className="text-gray-900 font-semibold flex justify-between">

        <p>Tổng chi phí:</p>
        <p>{formatPrice(totalPrice)}</p>
      </div>
    </div>
  )
}

const CarFixStatus = ({ id, status }: CarFixType) => {

  const changeProgress = useCallback(() => {
    if (status === 'processing') {
      return;
    }
    router.visit('/acar/update-status/' + id, {
      method: 'put',
      data: {
        status: 'processing',
      },
      onBefore: () => confirm('Bạn có chắc chắn bắt đầu làm không?'),
      preserveScroll: true,
    })
  }, [status])

  const changeDone = useCallback(() => {
    if (status === 'done') {
      return;
    }

    router.visit('/acar/update-status/' + id, {
      method: 'put',
      data: {
        status: 'done',
      },
      onBefore: () => confirm('Bạn có chắc chắn chuyển trạng thái hoàn thành không?'),
      preserveScroll: true,
    })
  }, [status])

  return (
    <div className="mt-2 p-2 bg-gray-300 rounded-md">
      <Stepper
        lineClassName={'bg-gray-400'}
        // activeLineClassName="bg-blue-600"
        activeStep={status === 'processing' ? 1 : (status === 'done' ? 2 : 0)}
      >
        <Step
          // activeClassName="bg-blue-600"
          // completedClassName="bg-blue-600"
          className="w-20 h-20 justify-center flex bg-gray-400"
        >
          <p className="text-sm text-center">{status === 'wait' ? 'đang chờ' : 'đã'} báo giá</p>
        </Step>
        <Step
          // activeClassName="bg-blue-600"
          // completedClassName="bg-blue-600"
          className="w-20 h-20 justify-center flex bg-gray-400" onClick={changeProgress}>
          <p className="text-sm text-center">{status === 'processing' ? 'đang' : ''} thực hiện</p>
        </Step>
        <Step
          // activeClassName="bg-blue-600"
          // completedClassName="bg-blue-600"
          className="w-20 h-20 justify-center bg-gray-400" onClick={changeDone}>
          <p className="text-sm text-center">{status === 'done' ? 'đã hoàn' : 'Hoàn'} thành</p>
        </Step>
      </Stepper>
    </div>
  )

  return (
    <div className="flex gap-2 justify-between">
      {status !== 'processing' && < Link href={'/acar/update-status/' + id}
        data={{
          status: 'processing',
        }}
        method="put"
        as='button'
        type="button"
        onBefore={() => confirm('Bạn có chắc chắn bắt đầu làm không?')}
        className="flex justify-end bg-gray-500 w-fit p-2 px-4 rounded-md">Làm ngay
      </Link>}

      {status === 'processing' && <Link href={'/acar/update-status/' + id}
        data={{
          status: 'done',
        }}
        method="put"
        as='button'
        type="button"
        onBefore={() => confirm('Bạn có chắc chắn chuyển trạng thái hoàn thành không?')}
        className="flex justify-end w-fit p-2 px-4 rounded-md bg-green-500 text-white">Hoàn thành
      </Link>}
    </div >
  )
}