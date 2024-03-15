<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <style>
    body {
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    table {
      height: 80%;
      width: 50%;
    }
  </style>
</head>

<body>
  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="background-color: #f5f5f5; font-family: Arial; font-size: 16px">
    <tbody>
      <tr>
        <td align="center" style="padding: 40px 0 0 0">
          <table border="0" cellpadding="0" cellspacing="0" width="600" style="
                background-color: #fff;
                border-radius: 8px;
                border: 1px solid #ebebeb;
              ">
            <tbody>
              <tr>
                <td align="center">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="
                        background-color: #213236;
                        border-radius: 8px 8px 0 0;
                        border-bottom: solid 8px #e55b27;
                        width: 100%;
                      ">
                    <tbody>
                      <tr>
                        <td align="center" style="padding: 20px 0 0 0">
                          <img width="200" height="64" src="https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/krear3d.png" alt="Logo" />
                          <img width="600" height="1" src="https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/pix.png" alt="Logo" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>

              <tr>
                <td align="center" style="padding: 32px 24px">
                  <table border="0" cellpadding="0" cellspacing="0" width="550">
                    <tbody>
                      <tr>
                        <td align="center">
                          <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                              <tr>
                                <td align="center">
                                  <img src="../../assets/img/check.webp" alt="Logo" width="80" height="80" />
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td align="center">
                          <h1 style="
                                font-size: 20px;
                                line-height: 24px;
                                margin: 0;
                                margin-top: 32px;
                              ">
                            Hola %CLIENT%,
                          </h1>
                          <p style="margin-top: 32px; width: 400px">
                            Tu solicitud de capacitación ha sido aprobada.
                            <br />
                            Enlace de acceso:
                            <?php if (isset($meet)) : ?>
                              <?= $meet ?>
                            <?php endif; ?>
                            <br>
                            A cargo de:
                            <?php if (isset($worker_name)) : ?>
                              <?= $worker_name ?>
                            <?php endif; ?>
                          </p>

                          <p style="
                                margin: 24px 0;
                                font-weight: 600;
                                font-size: 14px;
                              ">
                            Atento a nuestras redes sociales:
                          </p>

                          <table border="0" cellpadding="12" cellspacing="8" style="border-collapse: initial">
                            <tbody>
                              <tr>
                                <td align="center" style="
                                      background-color: #213236;
                                      border-radius: 30px;
                                    ">
                                  <a href="https://www.instagram.com/krear3d_peru/" target="_blank"><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/ins.png' width='20' height=20' alt='ico'></a>
                                </td>
                                <td align="center" style="
                                      background-color: #213236;
                                      border-radius: 30px;
                                    ">
                                  <a href="https://www.tiktok.com/@krear3d_peru " target="_blank"><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/tik.png' width='20' height=20' alt='ico'></a>
                                </td>
                                <td align="center" style="
                                      background-color: #213236;
                                      border-radius: 30px;
                                    ">
                                  <a href=" https://facebook.com/krear3d/" target="_blank"><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/fbk.png' width='20' height=20' alt='ico'></a>
                                </td>
                                <td align="center" style="
                                      background-color: #213236;
                                      border-radius: 30px;
                                    ">
                                  <a href="https://www.youtube.com/user/Krear3D" target="_blank"><img src='https://tiendakrear3d.com/wp-content/uploads/kyro11/mail/you.png' width='20' height=20' alt='ico'></a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>

              <tr>
                <td align="center">
                  <table border="0" cellpadding="24" cellspacing="0" width="100%" style="
                        background-color: #213236;
                        border-radius: 0 0 8px 8px;
                        border-top: solid 8px #e55b27;
                        width: 100%;
                      ">
                    <tbody>
                      <tr>
                        <td align="center" style="width: 100%">
                          <p style="
                                margin: 0;
                                font-size: 13px;
                                line-height: 20px;
                                color: #bdbaba;
                              ">
                            <b style="color: #fff">Atención al cliente:</b> Si
                            tienes más dudas o consultas, envíanos fotos o
                            videos de lo sucedido al número de soporte técnico
                            vía WhatsApp
                            <a href="https://api.whatsapp.com/send?phone=51970539751" style="color: #eb5b27"><b>+51 970 539 751</b></a>. La atención es únicamente por chat, por el
                            momento
                            <b style="color: #fff">no se atienden llamadas</b>.
                          </p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <td align="center">
          <table border="0" cellpadding="40" cellspacing="0" width="600">
            <tbody>
              <tr>
                <td align="center" style="color: #8a8a8a; font-size: 12px">
                  <p style="margin: 0">
                    Krear 3D - Fabricaciones Digitales del Peru S.A.
                  </p>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>