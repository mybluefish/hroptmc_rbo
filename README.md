Nanjing HROP TMC Role Booking Online(RBO)
===================================================================================

Nanjing HROP Toastmasters Club online role registion system

1. ��������
1) �����վ�õ��� mysql + php������֧�����������õ�������������������
2) �޸��⼸���ļ����������ݿ��˺����ã��޸ĳ��㽫�õ�
    config/admincfg.php
    classes/sqlutil.php
     classes/mysqlutil.php ������ļ�Ӧ�ò����ˣ���������������Ǹ�һ�£�
3�� �޸�classes/meetingdate.php�ļ����޸�Ϊ���Ǿ��ֲ�������
4) ����վԴ�ļ��ϴ������ݿ��ļ�����
     ��Ҫ���ݿ�����:
     members ��� �洢���ǻ�Ա����Ϣ�������ֶ�ClubId�Ǿ��ֲ��Լ�����Ա�ı�ţ��������ɣ�
     meetingagendarols ��񣺴洢��ɫԤԼ����Ϣ���洢�����ݸ�ʽ�ɲο� classes/fieldcontentinagenda.php�з���getFieldContentInAgendaFromInfoProvided֮ǰ��ע��
     roleNames ����ǻ���ɶ���ɫ��Ԫ������IsShown�ֶοɾ����Ƿ���ʾ����
     tmcclubs ��� ���������Ϣ�����Ծ�����ע���ɫ�ǣ��Ǳ����ֲ���Ա��ѡ�ľ��ֲ��б�
     exceptiondate ��񣺿��Թرսڼ��յ�����ѡ�������ʾΪ��ʾ��Ϣ  1 Ϊ����ʾ   2 Ϊ��ʵ�ض���Ϣ���ض���Ϣ��exceptionalReason�ֶα༭

����regularmeeting�ǿ����Զ����Ӽ�¼�⣬����������Ӽ�¼��ֱ�ӱ༭���ݿ⣬�ⲿ�ִ��뻹û��ʵ��
members��¼���޸ģ�����ͨ�����ֲ���Ա����վ www/members.php ҳ���¼�޸�

===================================================================================

hroptmc.libredesign.info
For Nanjing HROP TMC 

sstmc.libredesign.info
For Nanjing SS TMC

mandarin.libredesign.info
For Nanjing Mandarin TMC - With chinese translate

newhrop
New version of RBO under implementation

===================================================================================