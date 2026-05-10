import { Head, router, usePage } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout";
import React, { useEffect, useState } from "react";
import ReactApexChart from "react-apexcharts";
import { formatPrice } from "@/Helper/StringHelper";
import { listDateToArrayString } from "@/Pages/Amuaglobal/Helper";
import { CustomTimeTable } from "@/Pages/Amuaglobal/Components";
import TextInputField from "../components/form/TextInputField";

const ThongKe = ({ car_fix_dones }: any) => {

  const urlParams = new URLSearchParams(window.location.search);
  const params = Object.fromEntries(urlParams.entries());

  const [m, setM] = useState(params.m || new Date().getMonth() + 1);
  const [y, setY] = useState(params.y || new Date().getFullYear());

  const { props: { calendar }, } = usePage() as any;
  const [seletedDate, setSelectedDate] = useState<Date[]>([]);

  useEffect(() => {
    const defaultDate = [];
    if (params.from) {
      defaultDate.push(new Date(params.from));
    }
    if (params.to) {
      defaultDate.push(new Date(params.to));
    }
    setSelectedDate(defaultDate);
  }, [])

  useEffect(() => {
    let formatDate = listDateToArrayString(seletedDate.sort((a, b) => a - b));

    router.get('/acar/thong-ke', { ...params, from: formatDate[0], to: formatDate[1] }, {
      preserveState: true,
      preserveScroll: true,
    });
  }, [seletedDate])

  useEffect(() => {
    router.get('/acar/thong-ke', { ...params, m, y }, {
      preserveState: true,
      preserveScroll: true,
    });
  }, [m, y])

  return (
    <ContentLayout>
      <Head title="thống kê"></Head>
      <div className="min-h-screen">
        <div className="flex gap-2 p-4">
          <TextInputField label={'Tìm theo tháng'} type='number' min={1} max={12} value={m} onChange={(e) => setM(e.target.value)}></TextInputField>
          <TextInputField label={'Tìm theo năm'} type='number' min={2025} max={2050} value={y} onChange={(e) => setY(e.target.value)}></TextInputField>
        </div>
        {calendar && <CustomTimeTable selected={seletedDate} onChange={setSelectedDate}></CustomTimeTable>}
        <div className="p-4 bg-gray-100">
          {/* <ApexChart></ApexChart>
                <ApexChartColumn></ApexChartColumn> */}
          <ApexChartLineColumn data={car_fix_dones}></ApexChartLineColumn>
          <CarNumberChart data={car_fix_dones}></CarNumberChart>
          <StackedColumnChart data={car_fix_dones}></StackedColumnChart>
        </div>
      </div>
    </ContentLayout>
  )
}

export default ThongKe;

const ApexChart = () => {
  const [state, setState] = React.useState({

    series: [{
      name: "Desktops",
      data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
    }],
    options: {
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },
      title: {
        text: 'Product Trends by Month',
        align: 'left'
      },
      grid: {
        row: {
          colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.5
        },
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
      }
    },


  });



  return (
    <div>
      <div id="chart">
        <ReactApexChart options={state.options} series={state.series} type="line" height={350} />
      </div>
      <div id="html-dist"></div>
    </div>
  );
}

const ApexChartColumn = () => {
  const [state, setState] = React.useState({

    series: [{
      name: 'Inflation',
      data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
    }],
    options: {
      chart: {
        height: 350,
        type: 'bar',
      },
      plotOptions: {
        bar: {
          borderRadius: 10,
          dataLabels: {
            position: 'top', // top, center, bottom
          },
        }
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val + "%";
        },
        offsetY: -20,
        style: {
          fontSize: '12px',
          colors: ["#304758"]
        }
      },

      xaxis: {
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        position: 'top',
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        crosshairs: {
          fill: {
            type: 'gradient',
            gradient: {
              colorFrom: '#D8E3F0',
              colorTo: '#BED1E6',
              stops: [0, 100],
              opacityFrom: 0.4,
              opacityTo: 0.5,
            }
          }
        },
        tooltip: {
          enabled: true,
        }
      },
      yaxis: {
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false,
        },
        labels: {
          show: false,
          formatter: function (val) {
            return val + "%";
          }
        }

      },
      title: {
        text: 'Monthly Inflation in Argentina, 2002',
        floating: true,
        offsetY: 330,
        align: 'center',
        style: {
          color: '#444'
        }
      }
    },
  });



  return (
    <div>
      <div id="chart">
        <ReactApexChart options={state.options} series={state.series} type="bar" height={350} />
      </div>
      <div id="html-dist"></div>
    </div>
  );
}

const ApexChartLineColumn = ({ data }: { [key: string]: any[] }) => {
  let carCount: number[] = [];
  let totalCosts: number[] = [];

  /**
   * format collect data count of car length, total cost
   */
  Object.entries(data).forEach(([key, value]) => {
    carCount.push(value.length);
    totalCosts.push(value.reduce((a: any, b: any) => a + b.totalCost, 0));
  })

  const state = {
    series: [{
      name: 'Tổng doanh số',
      type: 'column',
      data: totalCosts
    }, {
      name: 'Số lượng xe hoàn thành',
      type: 'line',
      data: carCount
    }],
    options: {
      chart: {
        height: 350,
        type: 'line',
      },
      stroke: {
        width: [0, 3]
      },
      title: {
        text: 'Biến động doanh số và số lượng xe hoàn thành'
      },
      dataLabels: {
        enabled: true,
        enabledOnSeries: [0,],
        offsetY: -10,
      },
      labels: Object.keys(data),
      yaxis: [{
        title: {
          text: 'Tổng doanh số',
        },
      }, {
        opposite: true,
        title: {
          text: 'Số lượng xe hoàn thành'
        }
      }],
      tooltip: {
        y: [
          {
            // Formatter cho Series đầu tiên (Index 0)
            formatter: function (val: number) {
              return formatPrice(val);
            }
          },
          {
            // Formatter cho Series thứ hai (Index 1)
            formatter: function (val: number) {
              return val + " xe";
            }
          }
        ]
      }
    },
  }

  return (
    <ReactApexChart
      options={state.options}
      series={state.series}
      type="line"
      height={550}
    />
  );
}

const StackedColumnChart = ({ data }: { [key: string]: any[] }) => {
  let formatData: any[] = [];
  /**
  * format collect data count of car length, total cost
  */
  Object.entries(data).forEach(([key, value]) => {
    const totalCost = value.reduce((a: any, b: any) => a + b.totalCost, 0);
    formatData.push({
      name: key,
      data: [totalCost],
    });

  })

  const state = {
    series: formatData,
    options: {
      title: {
        text: 'Tổng doanh số : ' + formatPrice(formatData.reduce((a: any, b: any) => a + b.data[0], 0))
      },
      tooltip: {
        y:
          formatData.map((item, index) => {
            return (
              {
                // Formatter cho Series thứ hai (Index 1)
                formatter: function (val: number) {
                  return formatPrice(val);
                }
              }
            )
          })
      },
      chart: {
        type: 'bar',
        height: 350,
        stacked: true,
        toolbar: {
          show: true
        },
        zoom: {
          enabled: true
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          horizontal: true,
          borderRadius: 10,
          borderRadiusApplication: 'end', // 'around', 'end'
          borderRadiusWhenStacked: 'last', // 'all', 'last'
          dataLabels: {
            total: {
              enabled: true,
              style: {
                fontSize: '15px',
                fontWeight: 900
              }
            }
          }
        },
      },
      // xaxis: {
      //   type: 'datetime',
      //   categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
      //     '01/05/2011 GMT', '01/06/2011 GMT'
      //   ],
      // },
      legend: {
        position: 'right',
        offsetY: 40
      },
      fill: {
        opacity: 1
      }
    },
  };

  return (
    <div>
      <div id="chart">
        <ReactApexChart options={state.options} series={state.series} type="bar" height={240} />
      </div>
      <div id="html-dist"></div>
    </div>
  );
}

const CarNumberChart = ({ data }: { [key: string]: any[] }) => {
  let formatData: any[] = [];
  /**
  * format collect data count of car length, total cost
  */
  Object.entries(data).forEach(([key, value]) => {
    formatData.push({
      name: key,
      data: [value.length],
    });

  })
  const state = {
    series: formatData,
    options: {
      title: {
        text: 'Tổng số  xe đã hoàn thành: ' + formatData.reduce((a: any, b: any) => a + b.data[0], 0) + ' xe'
      },
      tooltip: {
        y:
          formatData.map((item, index) => {
            return (
              {
                // Formatter cho Series thứ hai (Index 1)
                formatter: function (val: number) {
                  return val + " xe";
                }
              }
            )
          })
      },
      chart: {
        type: 'bar',
        height: 220,
        stacked: true,
        toolbar: {
          show: true
        },
        zoom: {
          enabled: true
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          horizontal: true,
          borderRadius: 10,
          borderRadiusApplication: 'end', // 'around', 'end'
          borderRadiusWhenStacked: 'last', // 'all', 'last'
          dataLabels: {
            total: {
              enabled: true,
              style: {
                fontSize: '16px',
                fontWeight: 900
              }
            }
          }
        },
      },
      // xaxis: {
      //   type: 'datetime',
      //   categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
      //     '01/05/2011 GMT', '01/06/2011 GMT'
      //   ],
      // },
      legend: {
        position: 'right',
        offsetY: 40
      },
      fill: {
        opacity: 1
      }
    },


  };

  return (
    <div>
      <div id="chart">
        <ReactApexChart options={state.options} series={state.series} type="bar" height={240} />
      </div>
      <div id="html-dist"></div>
    </div>
  );
}

