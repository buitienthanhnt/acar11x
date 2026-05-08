import { Head, useForm } from "@inertiajs/react";
import ContentLayout from "./Layout/ContentLayout";
import TextInputField from "./components/form/TextInputField";
import PrimaryButton from "@/Components/PrimaryButton";

const Setting = ({ configs }: any) => {
  
  const { data, setData, post, processing, errors, reset, recentlySuccessful } = useForm({
    company: '',
    address: '',
    phone: '',
    vat: '',
    mst: '',
    calendar: false,
    ...configs,
  });

  const submit = (e: any) => {
    e.preventDefault();
    post('/acar/setting-store');
  }

  return (
    <ContentLayout>
      <div className="container mx-auto p-4 space-y-1">
        <Head title="setting"></Head>
        <div className=" flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
          <TextInputField
            value={data.company}
            label={'Tên công ty'}
            onChange={(e) => setData('company', e.target.value)}
            placeholder={'company'}
          />
        </div>

        <div className=" flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
          <TextInputField
            value={data.address}
            label={'Địa chỉ'}
            onChange={(e) => setData('address', e.target.value)}
            placeholder={'address'}
          />
        </div>

        <div className=" flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
          <TextInputField
            value={data.phone}
            label={'Số điện thoại'}
            onChange={(e) => setData('phone', e.target.value)}
            placeholder={'phone'}
          />
        </div>
        <div className=" flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
          <TextInputField
            value={data.vat}
            label={'Thuế VAT(%)'}
            onChange={(e) => setData('vat', e.target.value)}
            placeholder={'vat'}
          />
        </div>
        <div className=" flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
          <TextInputField
            value={data.mst}
            label={'Mã số thuế'}
            onChange={(e) => setData('mst', e.target.value)}
            placeholder={'mst'}
          />
        </div>

        <div className=" p-4 space-x-2 bg-blue-gray-100">
          <span className="font-semibold">Thêm bộ lọc</span>
          <input
            className="w-5 h-5 rounded-md"
            checked={!!Number(data.calendar)}
            onChange={(e) => setData('calendar', e.target.checked)}
            placeholder={'bộ lọc'}
            type="checkbox"
          />
        </div>
        <PrimaryButton onClick={submit} disabled={processing} className="bg-blue-gray-400 w-full flex justify-center hover:bg-blue-gray-800 p-2 rounded-lg font-semibold text-lg text-white">Lưu cài đặt</PrimaryButton>
      </div>
    </ContentLayout>
  )
}

export default Setting;
