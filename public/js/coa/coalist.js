const coalist = [
  {
    text: 'ASET',
    kode_perk: '1',
    kode_induk: '0',
    data: {
      d_or_k: 'D',
      g_or_d: 'G',
      kode_induk: '0',
      kode_perk: '1',
      level_perk: 0,
      nama_perk: 'ASET',
      saldo_awal: 34000,
      type_perk: 'HARTA'
    },
    state: {
      opened: true
    },
    children: [
      {
        text: 'Kas',
        kode_perk: '101',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '101',
          level_perk: 1,
          nama_perk: 'Kas',
          saldo_awal: 10000,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Kas Teller',
            kode_perk: '10101',
            kode_induk: '101',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '101',
              kode_perk: '10101',
              level_perk: 2,
              nama_perk: 'Kas Teller',
              saldo_awal: 10000,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Kas Bendahara',
            kode_perk: '10102',
            kode_induk: '101',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '101',
              kode_perk: '10102',
              level_perk: 2,
              nama_perk: 'Kas Bendahara',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Kas Brankas',
            kode_perk: '10103',
            kode_induk: '101',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '101',
              kode_perk: '10103',
              level_perk: 2,
              nama_perk: 'Kas Brankas',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Kas Virtual Account',
            kode_perk: '10104',
            kode_induk: '101',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '101',
              kode_perk: '10104',
              level_perk: 2,
              nama_perk: 'Kas Virtual Account',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Kas Deposit EChannel',
            kode_perk: '10105',
            kode_induk: '101',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '101',
              kode_perk: '10105',
              level_perk: 2,
              nama_perk: 'Kas Deposit EChannel',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Penempatan Dana :',
        kode_perk: '102',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '102',
          level_perk: 1,
          nama_perk: 'Penempatan Dana :',
          saldo_awal: 24000,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Tabungan Pada Bank',
            kode_perk: '10201',
            kode_induk: '102',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '102',
              kode_perk: '10201',
              level_perk: 2,
              nama_perk: 'Tabungan Pada Bank',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Deposito Berjangka Pada Bank',
            kode_perk: '10202',
            kode_induk: '102',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '102',
              kode_perk: '10202',
              level_perk: 2,
              nama_perk: 'Deposito Berjangka Pada Bank',
              saldo_awal: 24000,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Giro Pada Bank',
            kode_perk: '10203',
            kode_induk: '102',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '102',
              kode_perk: '10203',
              level_perk: 2,
              nama_perk: 'Giro Pada Bank',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Piutang :',
        kode_perk: '103',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '103',
          level_perk: 1,
          nama_perk: 'Piutang :',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Piutang Wakalah Pembelian Barang',
            kode_perk: '10301',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10301',
              level_perk: 2,
              nama_perk: 'Piutang Wakalah Pembelian Barang',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Piutang Murobahah',
            kode_perk: '10302',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10302',
              level_perk: 2,
              nama_perk: 'Piutang Murobahah',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: '(Margin Murobahah Ditangguhkan)',
            kode_perk: '10303',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10303',
              level_perk: 2,
              nama_perk: '(Margin Murobahah Ditangguhkan)',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Piutang Salam',
            kode_perk: '10304',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10304',
              level_perk: 2,
              nama_perk: 'Piutang Salam',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Piutang Istishna`',
            kode_perk: '10305',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10305',
              level_perk: 2,
              nama_perk: 'Piutang Istishna`',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: '(Margin Istishna` Ditangguhkan)',
            kode_perk: '10306',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10306',
              level_perk: 2,
              nama_perk: '(Margin Istishna` Ditangguhkan)',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Piutang Qord',
            kode_perk: '10307',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10307',
              level_perk: 2,
              nama_perk: 'Piutang Qord',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Ijarah',
            kode_perk: '10308',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10308',
              level_perk: 2,
              nama_perk: 'Ijarah',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: '(Ujroh Ditangguhkan)',
            kode_perk: '10309',
            kode_induk: '103',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '103',
              kode_perk: '10309',
              level_perk: 2,
              nama_perk: '(Ujroh Ditangguhkan)',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Pembiayaan',
        kode_perk: '104',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '104',
          level_perk: 1,
          nama_perk: 'Pembiayaan',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Pembiayaan Mudhorobah',
            kode_perk: '10401',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10401',
              level_perk: 2,
              nama_perk: 'Pembiayaan Mudhorobah',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pembiayaan Musyarokah',
            kode_perk: '10402',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10402',
              level_perk: 2,
              nama_perk: 'Pembiayaan Musyarokah',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pembiayaan Muzaroah',
            kode_perk: '10403',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10403',
              level_perk: 2,
              nama_perk: 'Pembiayaan Muzaroah',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pembiayaan Mukhobaroh',
            kode_perk: '10404',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10404',
              level_perk: 2,
              nama_perk: 'Pembiayaan Mukhobaroh',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pembiayaan Musaqoh',
            kode_perk: '10405',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10405',
              level_perk: 2,
              nama_perk: 'Pembiayaan Musaqoh',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pembiayaan Mughorosah',
            kode_perk: '10406',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10406',
              level_perk: 2,
              nama_perk: 'Pembiayaan Mughorosah',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pembiayaan Lainnya',
            kode_perk: '10499',
            kode_induk: '104',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '104',
              kode_perk: '10499',
              level_perk: 2,
              nama_perk: 'Pembiayaan Lainnya',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Piutang Usaha',
        kode_perk: '105',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '105',
          level_perk: 1,
          nama_perk: 'Piutang Usaha',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Piutang Dagang',
            kode_perk: '10501',
            kode_induk: '105',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '105',
              kode_perk: '10501',
              level_perk: 2,
              nama_perk: 'Piutang Dagang',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Piutang Usaha Lainnya',
            kode_perk: '10599',
            kode_induk: '105',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '105',
              kode_perk: '10599',
              level_perk: 2,
              nama_perk: 'Piutang Usaha Lainnya',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: '(Penyisihan Penghapusan Pembiayaan)',
        kode_perk: '106',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'D',
          kode_induk: '1',
          kode_perk: '106',
          level_perk: 1,
          nama_perk: '(Penyisihan Penghapusan Pembiayaan)',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Aset Istishna` Dalam Penyelesaian',
        kode_perk: '107',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '107',
          level_perk: 1,
          nama_perk: 'Aset Istishna` Dalam Penyelesaian',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: '(Termin Istishna`)',
            kode_perk: '10701',
            kode_induk: '107',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '107',
              kode_perk: '10701',
              level_perk: 2,
              nama_perk: '(Termin Istishna`)',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Persedian',
        kode_perk: '108',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '108',
          level_perk: 1,
          nama_perk: 'Persedian',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Persedian Barang Dagang',
            kode_perk: '10801',
            kode_induk: '108',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '108',
              kode_perk: '10801',
              level_perk: 2,
              nama_perk: 'Persedian Barang Dagang',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Persedian Bahan Baku',
            kode_perk: '10802',
            kode_induk: '108',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '108',
              kode_perk: '10802',
              level_perk: 2,
              nama_perk: 'Persedian Bahan Baku',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Persedian Lainnya',
            kode_perk: '10899',
            kode_induk: '108',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '108',
              kode_perk: '10899',
              level_perk: 2,
              nama_perk: 'Persedian Lainnya',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Aset Ijaroh',
        kode_perk: '109',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'D',
          kode_induk: '1',
          kode_perk: '109',
          level_perk: 1,
          nama_perk: 'Aset Ijaroh',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Aset Tetap & Inventaris',
        kode_perk: '110',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'D',
          kode_induk: '1',
          kode_perk: '110',
          level_perk: 1,
          nama_perk: 'Aset Tetap & Inventaris',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        }
      },
      {
        text: '(Akumulasi Penyusutan)',
        kode_perk: '111',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'D',
          kode_induk: '1',
          kode_perk: '111',
          level_perk: 1,
          nama_perk: '(Akumulasi Penyusutan)',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Aset Lain-Lain',
        kode_perk: '112',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '1',
          kode_perk: '112',
          level_perk: 1,
          nama_perk: 'Aset Lain-Lain',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Piutang Pembelian Aset',
            kode_perk: '11201',
            kode_induk: '112',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '112',
              kode_perk: '11201',
              level_perk: 2,
              nama_perk: 'Piutang Pembelian Aset',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Aset Lainnya',
            kode_perk: '11299',
            kode_induk: '112',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '112',
              kode_perk: '11299',
              level_perk: 2,
              nama_perk: 'Aset Lainnya',
              saldo_awal: 0,
              type_perk: 'HARTA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'BDD',
        kode_perk: '113',
        kode_induk: '1',
        data: {
          d_or_k: 'D',
          g_or_d: 'D',
          kode_induk: '1',
          kode_perk: '113',
          level_perk: 1,
          nama_perk: 'BDD',
          saldo_awal: 0,
          type_perk: 'HARTA'
        },
        state: {
          opened: true
        }
      }
    ]
  },
  {
    text: 'LIABILITAS',
    kode_perk: '2',
    kode_induk: '0',
    data: {
      d_or_k: 'K',
      g_or_d: 'G',
      kode_induk: '0',
      kode_perk: '2',
      level_perk: 0,
      nama_perk: 'LIABILITAS',
      saldo_awal: 200000,
      type_perk: 'KEWAJIBAN'
    },
    state: {
      opened: true
    },
    children: [
      {
        text: 'Utang Yag Harus Segera Dibayar',
        kode_perk: '201',
        kode_induk: '2',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '2',
          kode_perk: '201',
          level_perk: 1,
          nama_perk: 'Utang Yag Harus Segera Dibayar',
          saldo_awal: 200000,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Bagi Hasil Tabungan YMHD',
            kode_perk: '20101',
            kode_induk: '201',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '201',
              kode_perk: '20101',
              level_perk: 2,
              nama_perk: 'Bagi Hasil Tabungan YMHD',
              saldo_awal: 200000,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Bagi Hasil Deposito YMHD',
            kode_perk: '20102',
            kode_induk: '201',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '201',
              kode_perk: '20102',
              level_perk: 2,
              nama_perk: 'Bagi Hasil Deposito YMHD',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Bonus Tabungan YMHD',
            kode_perk: '20103',
            kode_induk: '201',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '201',
              kode_perk: '20103',
              level_perk: 2,
              nama_perk: 'Bonus Tabungan YMHD',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pajak YMHD',
            kode_perk: '20104',
            kode_induk: '201',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '201',
              kode_perk: '20104',
              level_perk: 2,
              nama_perk: 'Pajak YMHD',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Uang Muka Murobahah',
            kode_perk: '20105',
            kode_induk: '201',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '201',
              kode_perk: '20105',
              level_perk: 2,
              nama_perk: 'Uang Muka Murobahah',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'SHU Yang Belum Dibagikan',
            kode_perk: '20106',
            kode_induk: '201',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '201',
              kode_perk: '20106',
              level_perk: 2,
              nama_perk: 'SHU Yang Belum Dibagikan',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Simpanan Wadiah',
        kode_perk: '202',
        kode_induk: '2',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '2',
          kode_perk: '202',
          level_perk: 1,
          nama_perk: 'Simpanan Wadiah',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Tabungan Mikropay',
            kode_perk: '20201',
            kode_induk: '202',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '202',
              kode_perk: '20201',
              level_perk: 2,
              nama_perk: 'Tabungan Mikropay',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Simpanan Harian',
            kode_perk: '20202',
            kode_induk: '202',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '202',
              kode_perk: '20202',
              level_perk: 2,
              nama_perk: 'Simpanan Harian',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Simpanan Digital',
            kode_perk: '20203',
            kode_induk: '202',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '202',
              kode_perk: '20203',
              level_perk: 2,
              nama_perk: 'Simpanan Digital',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Utang Usaha',
        kode_perk: '203',
        kode_induk: '2',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '2',
          kode_perk: '203',
          level_perk: 1,
          nama_perk: 'Utang Usaha',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Utang Dagang',
            kode_perk: '20301',
            kode_induk: '203',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '203',
              kode_perk: '20301',
              level_perk: 2,
              nama_perk: 'Utang Dagang',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Titipan Pendanaan Channelling',
            kode_perk: '20302',
            kode_induk: '203',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '203',
              kode_perk: '20302',
              level_perk: 2,
              nama_perk: 'Titipan Pendanaan Channelling',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Dana Talangan',
            kode_perk: '20303',
            kode_induk: '203',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '203',
              kode_perk: '20303',
              level_perk: 2,
              nama_perk: 'Dana Talangan',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Titipan',
        kode_perk: '204',
        kode_induk: '2',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '2',
          kode_perk: '204',
          level_perk: 1,
          nama_perk: 'Titipan',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Titipan Zakat',
            kode_perk: '20401',
            kode_induk: '204',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '204',
              kode_perk: '20401',
              level_perk: 2,
              nama_perk: 'Titipan Zakat',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Titipan Infaq / Shodaqoh',
            kode_perk: '20402',
            kode_induk: '204',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '204',
              kode_perk: '20402',
              level_perk: 2,
              nama_perk: 'Titipan Infaq / Shodaqoh',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Titipan Denda',
            kode_perk: '20403',
            kode_induk: '204',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '204',
              kode_perk: '20403',
              level_perk: 2,
              nama_perk: 'Titipan Denda',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Liabilitas Lainnya',
        kode_perk: '205',
        kode_induk: '2',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '2',
          kode_perk: '205',
          level_perk: 1,
          nama_perk: 'Liabilitas Lainnya',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        }
      }
    ]
  },
  {
    text: 'DANA SYIRKAH TEMPORER',
    kode_perk: '3',
    kode_induk: '0',
    data: {
      d_or_k: 'K',
      g_or_d: 'G',
      kode_induk: '0',
      kode_perk: '3',
      level_perk: 0,
      nama_perk: 'DANA SYIRKAH TEMPORER',
      saldo_awal: 0,
      type_perk: 'KEWAJIBAN'
    },
    state: {
      opened: true
    },
    children: [
      {
        text: 'Tabungan Pendidikan',
        kode_perk: '301',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '3',
          kode_perk: '301',
          level_perk: 1,
          nama_perk: 'Tabungan Pendidikan',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Tabungan Idul Fitri',
        kode_perk: '302',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '3',
          kode_perk: '302',
          level_perk: 1,
          nama_perk: 'Tabungan Idul Fitri',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Tabungan Qurban',
        kode_perk: '303',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '3',
          kode_perk: '303',
          level_perk: 1,
          nama_perk: 'Tabungan Qurban',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Tabungan Umroh',
        kode_perk: '304',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '3',
          kode_perk: '304',
          level_perk: 1,
          nama_perk: 'Tabungan Umroh',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Tabungan Berjangka',
        kode_perk: '305',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '3',
          kode_perk: '305',
          level_perk: 1,
          nama_perk: 'Tabungan Berjangka',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Tabungan Berjangka 1 Bulan',
            kode_perk: '30501',
            kode_induk: '305',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '305',
              kode_perk: '30501',
              level_perk: 2,
              nama_perk: 'Tabungan Berjangka 1 Bulan',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Tabungan Berjangka 3 Bulan',
            kode_perk: '30502',
            kode_induk: '305',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '305',
              kode_perk: '30502',
              level_perk: 2,
              nama_perk: 'Tabungan Berjangka 3 Bulan',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Tabungan Berjangka 6 Bulan',
            kode_perk: '30503',
            kode_induk: '305',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '305',
              kode_perk: '30503',
              level_perk: 2,
              nama_perk: 'Tabungan Berjangka 6 Bulan',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Tabungan Berjangka 12 Bulan',
            kode_perk: '30504',
            kode_induk: '305',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '305',
              kode_perk: '30504',
              level_perk: 2,
              nama_perk: 'Tabungan Berjangka 12 Bulan',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Tabungan Penyertaan Modal (Muqoyad)',
        kode_perk: '306',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '3',
          kode_perk: '306',
          level_perk: 1,
          nama_perk: 'Tabungan Penyertaan Modal (Muqoyad)',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Muqoyad Waserda',
            kode_perk: '30601',
            kode_induk: '306',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '306',
              kode_perk: '30601',
              level_perk: 2,
              nama_perk: 'Muqoyad Waserda',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Muqoyad Baitusyifa',
            kode_perk: '30602',
            kode_induk: '306',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '306',
              kode_perk: '30602',
              level_perk: 2,
              nama_perk: 'Muqoyad Baitusyifa',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Muqoyad Lainnya',
            kode_perk: '30603',
            kode_induk: '306',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '306',
              kode_perk: '30603',
              level_perk: 2,
              nama_perk: 'Muqoyad Lainnya',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Pendanaan Dari LKS',
        kode_perk: '307',
        kode_induk: '3',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '3',
          kode_perk: '307',
          level_perk: 1,
          nama_perk: 'Pendanaan Dari LKS',
          saldo_awal: 0,
          type_perk: 'KEWAJIBAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Pendanaan dari LPDB Kemenkop',
            kode_perk: '30701',
            kode_induk: '307',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '307',
              kode_perk: '30701',
              level_perk: 2,
              nama_perk: 'Pendanaan dari LPDB Kemenkop',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendanaan dari PIP Kemenkeu',
            kode_perk: '30702',
            kode_induk: '307',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '307',
              kode_perk: '30702',
              level_perk: 2,
              nama_perk: 'Pendanaan dari PIP Kemenkeu',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendanaan dari LPMUKP KKP',
            kode_perk: '30703',
            kode_induk: '307',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '307',
              kode_perk: '30703',
              level_perk: 2,
              nama_perk: 'Pendanaan dari LPMUKP KKP',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendanaan dari Bank Syariah',
            kode_perk: '30704',
            kode_induk: '307',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '307',
              kode_perk: '30704',
              level_perk: 2,
              nama_perk: 'Pendanaan dari Bank Syariah',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendanaan dari Fintech Syariah',
            kode_perk: '30705',
            kode_induk: '307',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '307',
              kode_perk: '30705',
              level_perk: 2,
              nama_perk: 'Pendanaan dari Fintech Syariah',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendanaan dari LKS Lainnya',
            kode_perk: '30706',
            kode_induk: '307',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '307',
              kode_perk: '30706',
              level_perk: 2,
              nama_perk: 'Pendanaan dari LKS Lainnya',
              saldo_awal: 0,
              type_perk: 'KEWAJIBAN'
            },
            state: {
              opened: true
            }
          }
        ]
      }
    ]
  },
  {
    text: 'EKUITAS',
    kode_perk: '4',
    kode_induk: '0',
    data: {
      d_or_k: 'K',
      g_or_d: 'G',
      kode_induk: '0',
      kode_perk: '4',
      level_perk: 0,
      nama_perk: 'EKUITAS',
      saldo_awal: 0,
      type_perk: 'MODAL'
    },
    state: {
      opened: true
    },
    children: [
      {
        text: 'Simpanan Pokok',
        kode_perk: '401',
        kode_induk: '4',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '4',
          kode_perk: '401',
          level_perk: 1,
          nama_perk: 'Simpanan Pokok',
          saldo_awal: 0,
          type_perk: 'MODAL'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Simpanan Wajib Umum',
        kode_perk: '402',
        kode_induk: '4',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '4',
          kode_perk: '402',
          level_perk: 1,
          nama_perk: 'Simpanan Wajib Umum',
          saldo_awal: 0,
          type_perk: 'MODAL'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Simpanan Wajib Khusus',
        kode_perk: '403',
        kode_induk: '4',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '4',
          kode_perk: '403',
          level_perk: 1,
          nama_perk: 'Simpanan Wajib Khusus',
          saldo_awal: 0,
          type_perk: 'MODAL'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Wakf / Hibah',
        kode_perk: '404',
        kode_induk: '4',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '4',
          kode_perk: '404',
          level_perk: 1,
          nama_perk: 'Wakf / Hibah',
          saldo_awal: 0,
          type_perk: 'MODAL'
        },
        state: {
          opened: true
        }
      },
      {
        text: 'Cadangan',
        kode_perk: '405',
        kode_induk: '4',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '4',
          kode_perk: '405',
          level_perk: 1,
          nama_perk: 'Cadangan',
          saldo_awal: 0,
          type_perk: 'MODAL'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Cadangan SHU Tahun Lalu',
            kode_perk: '40501',
            kode_induk: '405',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '405',
              kode_perk: '40501',
              level_perk: 2,
              nama_perk: 'Cadangan SHU Tahun Lalu',
              saldo_awal: 0,
              type_perk: 'MODAL'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Cadangan Pendidikan',
            kode_perk: '40502',
            kode_induk: '405',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '405',
              kode_perk: '40502',
              level_perk: 2,
              nama_perk: 'Cadangan Pendidikan',
              saldo_awal: 0,
              type_perk: 'MODAL'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Cadangan Dana Sosial',
            kode_perk: '40503',
            kode_induk: '405',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '405',
              kode_perk: '40503',
              level_perk: 2,
              nama_perk: 'Cadangan Dana Sosial',
              saldo_awal: 0,
              type_perk: 'MODAL'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Cadangan Resiko',
            kode_perk: '40504',
            kode_induk: '405',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '405',
              kode_perk: '40504',
              level_perk: 2,
              nama_perk: 'Cadangan Resiko',
              saldo_awal: 0,
              type_perk: 'MODAL'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Sisa Hasil Usaha Tahun Berjalan',
        kode_perk: '406',
        kode_induk: '4',
        data: {
          d_or_k: 'K',
          g_or_d: 'D',
          kode_induk: '4',
          kode_perk: '406',
          level_perk: 1,
          nama_perk: 'Sisa Hasil Usaha Tahun Berjalan',
          saldo_awal: 0,
          type_perk: 'LABA RUGI'
        },
        state: {
          opened: true
        }
      }
    ]
  },
  {
    text: 'PENDAPATAN',
    kode_perk: '5',
    kode_induk: '0',
    data: {
      d_or_k: 'K',
      g_or_d: 'G',
      kode_induk: '0',
      kode_perk: '5',
      level_perk: 0,
      nama_perk: 'PENDAPATAN',
      saldo_awal: 0,
      type_perk: 'PENDAPATAN'
    },
    state: {
      opened: true
    },
    children: [
      {
        text: 'Pendapatan Operasional',
        kode_perk: '501',
        kode_induk: '5',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '5',
          kode_perk: '501',
          level_perk: 1,
          nama_perk: 'Pendapatan Operasional',
          saldo_awal: 0,
          type_perk: 'PENDAPATAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Pendapatan Margin Jual Beli',
            kode_perk: '50101',
            kode_induk: '501',
            data: {
              d_or_k: 'K',
              g_or_d: 'G',
              kode_induk: '501',
              kode_perk: '50101',
              level_perk: 2,
              nama_perk: 'Pendapatan Margin Jual Beli',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            },
            children: [
              {
                text: 'Pendapatan Margin Murobahah',
                kode_perk: '5010101',
                kode_induk: '50101',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50101',
                  kode_perk: '5010101',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Margin Murobahah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Margin Istishna`',
                kode_perk: '5010102',
                kode_induk: '50101',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50101',
                  kode_perk: '5010102',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Margin Istishna`',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Margin Salam',
                kode_perk: '5010103',
                kode_induk: '50101',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50101',
                  kode_perk: '5010103',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Margin Salam',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              }
            ]
          },
          {
            text: 'Pendapatan Ujroh Jasa',
            kode_perk: '50102',
            kode_induk: '501',
            data: {
              d_or_k: 'K',
              g_or_d: 'G',
              kode_induk: '501',
              kode_perk: '50102',
              level_perk: 2,
              nama_perk: 'Pendapatan Ujroh Jasa',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            },
            children: [
              {
                text: 'Pendapatan Ujroh Ijaroh Multijasa',
                kode_perk: '5010201',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010201',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Ijaroh Multijasa',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Ijaroh',
                kode_perk: '5010202',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010202',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Ijaroh',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Ijaroh IMBT',
                kode_perk: '5010203',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010203',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Ijaroh IMBT',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Ijaroh IMFZ',
                kode_perk: '5010204',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010204',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Ijaroh IMFZ',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Rohn',
                kode_perk: '5010205',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010205',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Rohn',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Kafalah',
                kode_perk: '5010206',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010206',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Kafalah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Hawalah',
                kode_perk: '5010207',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010207',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Hawalah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Ujroh Wakalah',
                kode_perk: '5010208',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010208',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Ujroh Wakalah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan ujroh Dju`alah',
                kode_perk: '5010209',
                kode_induk: '50102',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50102',
                  kode_perk: '5010209',
                  level_perk: 3,
                  nama_perk: 'Pendapatan ujroh Dju`alah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              }
            ]
          },
          {
            text: 'Pendapatan Bagi Hasil Syirkah',
            kode_perk: '50103',
            kode_induk: '501',
            data: {
              d_or_k: 'K',
              g_or_d: 'G',
              kode_induk: '501',
              kode_perk: '50103',
              level_perk: 2,
              nama_perk: 'Pendapatan Bagi Hasil Syirkah',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            },
            children: [
              {
                text: 'Pendapatan Bagi Hasil Mudhorobah',
                kode_perk: '5010301',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010301',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Mudhorobah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Musyarokah',
                kode_perk: '5010302',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010302',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Musyarokah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Muzaroah',
                kode_perk: '5010303',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010303',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Muzaroah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Mukhobaroh',
                kode_perk: '5010304',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010304',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Mukhobaroh',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Musaqoh',
                kode_perk: '5010305',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010305',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Musaqoh',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Mughorosah',
                kode_perk: '5010306',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010306',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Mughorosah',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Lainnya',
                kode_perk: '5010399',
                kode_induk: '50103',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50103',
                  kode_perk: '5010399',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Lainnya',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              }
            ]
          },
          {
            text: 'Pendapatan Usaha',
            kode_perk: '50104',
            kode_induk: '501',
            data: {
              d_or_k: 'K',
              g_or_d: 'G',
              kode_induk: '501',
              kode_perk: '50104',
              level_perk: 2,
              nama_perk: 'Pendapatan Usaha',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            },
            children: [
              {
                text: 'Pendapatan Usaha Dagang / Bagi Hasil Tijaroh',
                kode_perk: '5010401',
                kode_induk: '50104',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50104',
                  kode_perk: '5010401',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Usaha Dagang / Bagi Hasil Tijaroh',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Bagi Hasil Rumah Herbal Baitus Syifa',
                kode_perk: '5010402',
                kode_induk: '50104',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50104',
                  kode_perk: '5010402',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Bagi Hasil Rumah Herbal Baitus Syifa',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Pendapatan Usaha Lainnya',
                kode_perk: '5010499',
                kode_induk: '50104',
                data: {
                  d_or_k: 'K',
                  g_or_d: 'D',
                  kode_induk: '50104',
                  kode_perk: '5010499',
                  level_perk: 3,
                  nama_perk: 'Pendapatan Usaha Lainnya',
                  saldo_awal: 0,
                  type_perk: 'PENDAPATAN'
                },
                state: {
                  opened: true
                }
              }
            ]
          }
        ]
      },
      {
        text: 'Pendapatan Non Operasional',
        kode_perk: '502',
        kode_induk: '5',
        data: {
          d_or_k: 'K',
          g_or_d: 'G',
          kode_induk: '5',
          kode_perk: '502',
          level_perk: 1,
          nama_perk: 'Pendapatan Non Operasional',
          saldo_awal: 0,
          type_perk: 'PENDAPATAN'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Pendapatan Bagi Hasil Tabungan Bank Syariah',
            kode_perk: '50201',
            kode_induk: '502',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '502',
              kode_perk: '50201',
              level_perk: 2,
              nama_perk: 'Pendapatan Bagi Hasil Tabungan Bank Syariah',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendapatan Bagi Hasil Deposito Bank Syariah',
            kode_perk: '50202',
            kode_induk: '502',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '502',
              kode_perk: '50202',
              level_perk: 2,
              nama_perk: 'Pendapatan Bagi Hasil Deposito Bank Syariah',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendapatan Administrasi Pembiayaan',
            kode_perk: '50203',
            kode_induk: '502',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '502',
              kode_perk: '50203',
              level_perk: 2,
              nama_perk: 'Pendapatan Administrasi Pembiayaan',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendapatan Keuntungan Penjualan ATI',
            kode_perk: '50204',
            kode_induk: '502',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '502',
              kode_perk: '50204',
              level_perk: 2,
              nama_perk: 'Pendapatan Keuntungan Penjualan ATI',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendapatan Penyisihan Penghapusan Pembiayaan',
            kode_perk: '50205',
            kode_induk: '502',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '502',
              kode_perk: '50205',
              level_perk: 2,
              nama_perk: 'Pendapatan Penyisihan Penghapusan Pembiayaan',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Pendapatan Bagi Hasil LKS Lainnya',
            kode_perk: '50299',
            kode_induk: '502',
            data: {
              d_or_k: 'K',
              g_or_d: 'D',
              kode_induk: '502',
              kode_perk: '50299',
              level_perk: 2,
              nama_perk: 'Pendapatan Bagi Hasil LKS Lainnya',
              saldo_awal: 0,
              type_perk: 'PENDAPATAN'
            },
            state: {
              opened: true
            }
          }
        ]
      }
    ]
  },
  {
    text: 'BIAYA',
    kode_perk: '6',
    kode_induk: '0',
    data: {
      d_or_k: 'D',
      g_or_d: 'G',
      kode_induk: '0',
      kode_perk: '6',
      level_perk: 0,
      nama_perk: 'BIAYA',
      saldo_awal: 0,
      type_perk: 'BIAYA'
    },
    state: {
      opened: true
    },
    children: [
      {
        text: 'Biaya Operasional',
        kode_perk: '601',
        kode_induk: '6',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '6',
          kode_perk: '601',
          level_perk: 1,
          nama_perk: 'Biaya Operasional',
          saldo_awal: 0,
          type_perk: 'BIAYA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Biaya Bagi Hasil',
            kode_perk: '60101',
            kode_induk: '601',
            data: {
              d_or_k: 'D',
              g_or_d: 'G',
              kode_induk: '601',
              kode_perk: '60101',
              level_perk: 2,
              nama_perk: 'Biaya Bagi Hasil',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            },
            children: [
              {
                text: 'Biaya Bagi Hasil Tabungan Pendidikan',
                kode_perk: '6010101',
                kode_induk: '60101',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60101',
                  kode_perk: '6010101',
                  level_perk: 3,
                  nama_perk: 'Biaya Bagi Hasil Tabungan Pendidikan',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Biaya Bagi Hasil Tabungan Idul Fitri',
                kode_perk: '6010102',
                kode_induk: '60101',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60101',
                  kode_perk: '6010102',
                  level_perk: 3,
                  nama_perk: 'Biaya Bagi Hasil Tabungan Idul Fitri',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Biaya Bagi Hasil Tabungan Qurban',
                kode_perk: '6010103',
                kode_induk: '60101',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60101',
                  kode_perk: '6010103',
                  level_perk: 3,
                  nama_perk: 'Biaya Bagi Hasil Tabungan Qurban',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Biaya Bagi Hasil Tabungan Umroh',
                kode_perk: '6010104',
                kode_induk: '60101',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60101',
                  kode_perk: '6010104',
                  level_perk: 3,
                  nama_perk: 'Biaya Bagi Hasil Tabungan Umroh',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Biaya Bagi Hasil Tabungan Berjangka',
                kode_perk: '6010105',
                kode_induk: '60101',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60101',
                  kode_perk: '6010105',
                  level_perk: 3,
                  nama_perk: 'Biaya Bagi Hasil Tabungan Berjangka',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Biaya Bagi Hasil Muqoyadah',
                kode_perk: '6010106',
                kode_induk: '60101',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60101',
                  kode_perk: '6010106',
                  level_perk: 3,
                  nama_perk: 'Biaya Bagi Hasil Muqoyadah',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              }
            ]
          },
          {
            text: 'Biaya Gaji / Honor',
            kode_perk: '60102',
            kode_induk: '601',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '601',
              kode_perk: '60102',
              level_perk: 2,
              nama_perk: 'Biaya Gaji / Honor',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Penyusutan',
            kode_perk: '60103',
            kode_induk: '601',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '601',
              kode_perk: '60103',
              level_perk: 2,
              nama_perk: 'Biaya Penyusutan',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Penyisihan Penghapusan Pembiayaan',
            kode_perk: '60104',
            kode_induk: '601',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '601',
              kode_perk: '60104',
              level_perk: 2,
              nama_perk: 'Biaya Penyisihan Penghapusan Pembiayaan',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Bonus Wadiah',
            kode_perk: '60105',
            kode_induk: '601',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '601',
              kode_perk: '60105',
              level_perk: 2,
              nama_perk: 'Biaya Bonus Wadiah',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Operasional Lainnya',
            kode_perk: '60106',
            kode_induk: '601',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '601',
              kode_perk: '60106',
              level_perk: 2,
              nama_perk: 'Biaya Operasional Lainnya',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          }
        ]
      },
      {
        text: 'Biaya Non Operasional',
        kode_perk: '602',
        kode_induk: '6',
        data: {
          d_or_k: 'D',
          g_or_d: 'G',
          kode_induk: '6',
          kode_perk: '602',
          level_perk: 1,
          nama_perk: 'Biaya Non Operasional',
          saldo_awal: 0,
          type_perk: 'BIAYA'
        },
        state: {
          opened: true
        },
        children: [
          {
            text: 'Biaya Kerugian Penjualan',
            kode_perk: '60201',
            kode_induk: '602',
            data: {
              d_or_k: 'D',
              g_or_d: 'G',
              kode_induk: '602',
              kode_perk: '60201',
              level_perk: 2,
              nama_perk: 'Biaya Kerugian Penjualan',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            },
            children: [
              {
                text: 'Biaya Kerugian Penjualan ATI',
                kode_perk: '6020101',
                kode_induk: '60201',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60201',
                  kode_perk: '6020101',
                  level_perk: 3,
                  nama_perk: 'Biaya Kerugian Penjualan ATI',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              },
              {
                text: 'Biaya Kerugian Penjualan Barang Dagang',
                kode_perk: '6020102',
                kode_induk: '60201',
                data: {
                  d_or_k: 'D',
                  g_or_d: 'D',
                  kode_induk: '60201',
                  kode_perk: '6020102',
                  level_perk: 3,
                  nama_perk: 'Biaya Kerugian Penjualan Barang Dagang',
                  saldo_awal: 0,
                  type_perk: 'BIAYA'
                },
                state: {
                  opened: true
                }
              }
            ]
          },
          {
            text: 'Biaya Sumbangan',
            kode_perk: '60202',
            kode_induk: '602',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '602',
              kode_perk: '60202',
              level_perk: 2,
              nama_perk: 'Biaya Sumbangan',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Pajak Penghasilan dari Bank Syariah',
            kode_perk: '60203',
            kode_induk: '602',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '602',
              kode_perk: '60203',
              level_perk: 2,
              nama_perk: 'Biaya Pajak Penghasilan dari Bank Syariah',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Kerugian Selisih KURS',
            kode_perk: '60204',
            kode_induk: '602',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '602',
              kode_perk: '60204',
              level_perk: 2,
              nama_perk: 'Biaya Kerugian Selisih KURS',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Administrasi',
            kode_perk: '60205',
            kode_induk: '602',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '602',
              kode_perk: '60205',
              level_perk: 2,
              nama_perk: 'Biaya Administrasi',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          },
          {
            text: 'Biaya Kerugian Bencana Alam',
            kode_perk: '60206',
            kode_induk: '602',
            data: {
              d_or_k: 'D',
              g_or_d: 'D',
              kode_induk: '602',
              kode_perk: '60206',
              level_perk: 2,
              nama_perk: 'Biaya Kerugian Bencana Alam',
              saldo_awal: 0,
              type_perk: 'BIAYA'
            },
            state: {
              opened: true
            }
          }
        ]
      }
    ]
  },
  {
    text: 'Taksiran Pajak Penghasilan',
    kode_perk: '7',
    kode_induk: '0',
    data: {
      d_or_k: 'D',
      g_or_d: 'D',
      kode_induk: '0',
      kode_perk: '7',
      level_perk: 0,
      nama_perk: 'Taksiran Pajak Penghasilan',
      saldo_awal: 0,
      type_perk: 'PAJAK'
    },
    state: {
      opened: true
    }
  },
  {
    text: 'Rekening Administratif',
    kode_perk: '8',
    kode_induk: '0',
    data: {
      d_or_k: 'D',
      g_or_d: 'D',
      kode_induk: '0',
      kode_perk: '8',
      level_perk: 0,
      nama_perk: 'Rekening Administratif',
      saldo_awal: 0,
      type_perk: 'ADMINISTRASI'
    },
    state: {
      opened: true
    }
  }
];
export default coalist;
