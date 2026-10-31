import React, { useCallback, useMemo, useState } from "react";
import ContentLayout from "../Layout/ContentLayout";
import { Activity, CarFixType } from "../types/CarType";
import { CheckCircleIcon, PlusCircleIcon, TrashIcon, WrenchScrewdriverIcon, XCircleIcon } from "@heroicons/react/24/solid";
import TextInputField from "../components/form/TextInputField";
import { Head, Link, router, useForm, usePage, WhenVisible } from "@inertiajs/react";
import { formatPrice } from "@/Helper/StringHelper";
import { Step, Stepper } from "@material-tailwind/react";
import Modal from "@/Components/Modal";
import { Paginate } from "@/Components/Custom";
import { ProductItem } from "@/Pages/Amuaglobal/Screens/Adminhtml/Product/ProductList";
import { ProductType } from "@/Pages/Amuaglobal/types/Product";
import { useUrlParam } from "@/Pages/Amuaglobal/hooks/useUrlParam";
import { debounce } from "lodash";
import { PageWithLayout } from "../types/ExtendType";

const CarFix: PageWithLayout<{ carFix: CarFixType }> = ({ carFix }) => {

  return (
    <div className="container flex-1 p-1 mx-auto space-y-2 bg-white">
      <Head title="nội dung công việc"></Head>
      <CarFixInfo {...carFix}></CarFixInfo>
      <ActivityForm {...carFix}></ActivityForm>
      <ActivityList activities={carFix.activities}></ActivityList>
      <CarFixStatus {...carFix}></CarFixStatus>
      <div className="flex justify-between p-2">
        <Link href={'/acar/lenh-sua-chua/' + carFix.id} className="p-2 px-4 text-white bg-gray-800 rounded-md">Lệnh sửa chữa</Link>
        <Link href={'/acar/hoa-don/' + carFix.id} className="p-2 px-4 text-white bg-gray-900 rounded-md">Báo giá</Link>
      </div>
    </div>
  );
}

CarFix.layout = (page: React.ReactNode) => <ContentLayout children={page} />

export default CarFix;

export const CarFixInfo = ({ car, km }: CarFixType) => {
  return (
    <div className="flex p-2 bg-gray-300 rounded-md">
      <div className="flex flex-col flex-1 p-2">
        <b>Thông tin khách hàng:</b>
        <b>Khách hàng: {car?.customer?.name}</b>
        <b>Số điện thoại: {car?.customer?.phone}</b>
        <i>Địa chỉ: {car?.customer?.address}</i>
      </div>
      <Link className="flex flex-col flex-1 p-2 rounded-md bg-blue-gray-200" href={route('car.history', { id: car.key })}>
        <b>Thông tin xe:</b>
        <b>Biển số: {car.key}</b>
        <b>Loại: {car.suspension}-{car.type}</b>
        {car.vin && <b>VIN: {car.vin}</b>}
        <b>Số Km đã đi: {km} (km)</b>
        {/* <b className="text-purple-700">Trạng thái: {status_label}</b> */}
      </Link>
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
    dvt: '',
    product: null as ProductType | null,
  });

  const onSelectProduct = (product: ProductType) => {
    setData('title', product.name);
    setData('price', product.price);
    setData('dvt', product?.dvt || '');
    setData('product', product);
  }

  const onClearSelectedProduct = () => {
    setData('title', '');
    setData('price', 0);
    setData('dvt', '');
    setData('product', null);
  }

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
    <div className="relative flex flex-col gap-2 p-2 bg-gray-300 rounded-lg">
      <div className="flex justify-center text-xl font-semibold">
        Khai báo công việc và báo giá
      </div>
      <div className="flex items-end space-x-2">
        <div className="flex flex-col justify-center flex-1">
          <b>Nội dung:</b>
          <textarea rows={4} value={data.title} onChange={e => setData('title', e.target.value)} placeholder="nội dung thực hiện" className="w-full overflow-hidden bg-transparent border-gray-800 rounded-md"></textarea>
        </div>

        <div className="flex flex-col flex-1 gap-1">
          <div className="flex justify-end">
            <WrenchModal onSelectProduct={onSelectProduct}></WrenchModal>
          </div>
          <div className="flex flex-col justify-center flex-1 gap-2">
            {data.product && <>
              <b className="text-green-500">Phụ tùng đang chọn:  </b>
              <div className="relative flex gap-2">
                <XCircleIcon className="absolute text-gray-900 size-8 -top-1 -right-1 hover:text-red-900"
                  onClick={onClearSelectedProduct}></XCircleIcon>
                <img src={data?.product.image} className="object-cover rounded-md size-20" alt="" />
                <div className="flex flex-col">
                  <b>{data?.product.name} (Moidel: {data?.product.sku})</b>
                  <b className="text-purple-500">{formatPrice(data?.product.price)}</b>
                </div>
              </div>
            </>}
            <b>Chi phí(đơn vị nghìn vnđ):</b>
            <TextInputField type="number" name="price" value={data.price} onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('price', Number(e.target.value))} className="w-full" placeholder="chi phí"></TextInputField>
          </div>

          <div className="flex space-x-1">
            <div className="flex flex-col justify-center flex-1">
              <b>Số lượng:</b>
              <TextInputField type="number" name="qty" value={data.qty} min={1} onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('qty', Number(e.target.value))} className="w-full" placeholder="số lượng"></TextInputField>
            </div>

            <div className="flex flex-col justify-center flex-1">
              <b>Đơn vị tính:</b>
              <TextInputField
                list="browserx"
                type="text"
                name="dvt"
                value={data.dvt}
                onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('dvt', e.target.value)}
                className="w-full"
                placeholder="đơn vị tính">
              </TextInputField>
              <datalist id="browserx">
                <option value="Lít" />
                <option value="Cái" />
                <option value="Chiếc" />
                <option value="Chai" />
                <option value="Can" />
                <option value="Công" />
                <option value="Bộ" />
                <option value="Hộp" />
                <option value="Lần" />
                <option value="Lượt" />
                <option value="Ngày" />
                <option value="Giờ" />
                <option value="Phút" />
                <option value="Km" />
                <option value="Mile" />
              </datalist>
            </div>
          </div>
        </div>
      </div>
      <div className="flex justify-end ">
        {data.title && <div className="absolute flex font-semibold left-1">
          {data.title}: {data.price < 0 ? '-' : ''} {formatPrice(data.price)} X {data.qty} = {formatPrice(data.price * data.qty)}
        </div>}
        {<PlusCircleIcon title="thêm hạng mục" onClick={onAddItem} className="text-gray-500 size-14 hover:text-gray-900"></PlusCircleIcon>}
      </div>
    </div>
  )
}

const WrenchModal = ({ onSelectProduct }: { onSelectProduct?: (product: any) => void }) => {
  const k_search = useUrlParam('k_search');
  const { products, } = usePage().props as unknown as { products: any };
  const [showForm, setShowForm] = useState(false);

  const onSeacch = debounce((value: string) => {
    if (value.length > 2 || value.length === 0) {
      router.visit(window.location.href, {
        method: 'get',
        data: {
          k_search: value,
          product_page: 1
        },
        preserveState: true,
        only: ['products'],
      });
    }
  }, 300);

  return (
    <>
      <p className="mr-2 text-xl font-semibold text-purple-600">Chọn phụ tùng:</p>
      <WrenchScrewdriverIcon className="inline-block size-7 hover:cursor-pointer" color="#8e24aa" onClick={() => setShowForm(true)}></WrenchScrewdriverIcon>
      <Modal
        show={showForm}
      >
        <div className="flex items-center justify-between p-2">
          <h2 className="text-xl font-semibold">Chọn phụ tùng:</h2>
          <XCircleIcon className="w-10 h-10" onClick={() => {
            setShowForm(false)
          }}></XCircleIcon>
        </div>
        <WhenVisible fallback={<div className="flex items-center justify-center p-4 font-semibold">Loading...</div>} data={'products'}>
          <div className="p-4 h-[60vh] flex flex-col flex-1 w-auto overflow-y-scroll [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div className="my-2">
              <TextInputField name="" defaultValue={k_search} onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                onSeacch(e.target.value);
              }} placeholder="tìm kiếm"></TextInputField>
            </div>
            <div className="flex-col flex-1">
              {products?.data?.map((product: ProductType) => <ProductItem key={product.id} product={product} onSelectProduct={(p: any) => {
                onSelectProduct?.(p);
                setShowForm(false);
              }} />)}
            </div>
            <Paginate
              pageSize={products?.last_page}
              currentPage={products?.current_page}
              url={window.location.href}
              pageName={'product_page'}
              options={{
                only: ['products'],
                preserveState: true,
              }}
            />
          </div>
        </WhenVisible>
      </Modal>
    </>
  );
}

const ActivityList = ({ activities }: { activities: Activity[] }) => {
  const { carFix: { vat, id } } = usePage().props as unknown as { carFix: CarFixType };
  const totalPrice = useMemo(() => {
    return activities.reduce((total, activity) => total + activity.price * activity.qty, 0);
  }, [activities])

  if (!activities.length) {
    return null;
  }

  return (
    <div className="relative flex flex-col gap-2 p-2 bg-gray-300 rounded-lg">
      <div className="flex justify-center text-xl font-semibold">
        Chi tiết công việc và báo giá
      </div>
      <div className="">
        {activities.map((activity, index) => {
          return <div key={index} className="flex justify-between py-2 border-b border-gray-900">
            <div className="text-lg font-semibold text-gray-900">
              [{index + 1}].
              {activity.product &&
                <img src={activity.product.image}
                  alt={activity.product.name}
                  className="inline-block object-cover w-16 h-16 mr-2 rounded-md" />
              }
              {activity.title}{
                activity?.product && <Link href={`/adminhtml/product/${activity.product?.id}`}>({activity.product?.alias})</Link>
              }: {activity.price < 0 ? '-' : ''}
              {formatPrice(activity.price)} X {activity.qty} {activity.dvt ? `(${activity.dvt})` : ''} =
            </div>
            <div className="flex items-center space-x-2">
              <p>{activity.price < 0 ? '-' : ''}{formatPrice(activity.price * activity.qty)}</p>
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
      <div className="flex justify-end p-2 py-1 border-b border-gray-900 text-end">
        <Link className={`flex ${vat ? 'text-red-900' : 'text-gray-900'}`}
          method="post"
          as="button"
          type="button"
          onBefore={() => confirm(`Bạn có chắc chắn muốn ${vat ? 'Bỏ qua VAT' : 'Áp dụng VAT'} không?`)}
          href={route('acar.apply.vat', { id: id })}>
          {vat ? 'Bỏ qua VAT' : 'Áp dụng VAT'} {vat ? `${vat} %` : ''}
          <CheckCircleIcon className={`size-5 ${vat ? 'text-red-900' : 'text-gray-900'}`}></CheckCircleIcon>
        </Link>
      </div>
      <div className="mt-1">
        <div className="flex justify-between font-semibold text-gray-900">
          <p>Tổng chi phí:</p>
          <p>{formatPrice(totalPrice)}</p>
        </div>
        {!!vat && <div className="flex justify-between font-semibold text-gray-900">
          <p>VAT({vat}%):</p>
          <p>{formatPrice(Math.round(totalPrice * vat / 100))}</p>
        </div>}
        {!!vat && <div>
          <div className="flex justify-between font-semibold text-gray-900">
            <p>Tổng Hóa đơn:</p>
            <p>{formatPrice(totalPrice + Math.round(totalPrice * vat / 100))}</p>
          </div>
        </div>}
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
    <div className="p-2 mt-2 bg-gray-300 rounded-md">
      <Stepper
        lineClassName={'bg-gray-400'}
        // activeLineClassName="bg-blue-600"
        activeStep={status === 'processing' ? 1 : (status === 'done' ? 2 : 0)}
      >
        <Step
          // activeClassName="bg-blue-600"
          // completedClassName="bg-blue-600"
          className="flex justify-center w-20 h-20 bg-gray-400"
        >
          <p className="text-sm text-center">{status === 'wait' ? 'Đang chờ' : 'Đã'} báo giá</p>
        </Step>
        <Step
          // activeClassName="bg-blue-600"
          // completedClassName="bg-blue-600"
          className="flex justify-center w-20 h-20 bg-gray-400" onClick={changeProgress}>
          <p className="text-sm text-center">{status === 'processing' ? 'Đang thực hiện' : 'Thực hiện'}</p>
        </Step>
        <Step
          // activeClassName="bg-blue-600"
          // completedClassName="bg-blue-600"
          className="justify-center w-20 h-20 bg-gray-400" onClick={changeDone}>
          <p className="text-sm text-center">{status === 'done' ? 'Đã hoàn' : 'Hoàn'} thành</p>
        </Step>
      </Stepper>
    </div>
  )
}
